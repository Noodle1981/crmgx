<?php
namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Sequence;
use App\Models\SequenceEnrollment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Task;

class EnrollmentController extends Controller
{
    public function index()
    {
        $enrollments = SequenceEnrollment::where('user_id', Auth::id())
            ->where('status', 'active')
            ->with(['enrollable', 'sequence', 'currentStep'])
            ->latest('next_step_due_at')
            ->get();

        // Group enrollments by their polymorphic type
        $enrollmentsByEnrollableType = $enrollments->groupBy('enrollable_type');

        $tasks = collect();

        // For each type, fetch the relevant tasks in a single query
        foreach ($enrollmentsByEnrollableType as $type => $enrollmentsOfType) {
            $ids = $enrollmentsOfType->pluck('enrollable_id');
            $tasks = $tasks->merge(
                Task::where('taskable_type', $type)
                    ->whereIn('taskable_id', $ids)
                    ->where('type', 'video_call')
                    ->get()
            );
        }

        // Group tasks by their polymorphic parent for easy lookup
        $tasksByEnrollable = $tasks->groupBy(function ($task) {
            return $task->taskable_type . '-' . $task->taskable_id;
        });

        // Attach the latest task to each enrollment
        foreach ($enrollments as $enrollment) {
            $key = $enrollment->enrollable_type . '-' . $enrollment->enrollable_id;
            if (isset($tasksByEnrollable[$key])) {
                $enrollment->setRelation('latestVideoCallTask', $tasksByEnrollable[$key]->sortByDesc('created_at')->first());
            } else {
                $enrollment->setRelation('latestVideoCallTask', null);
            }
        }

        $notifications = Auth::user()->unreadNotifications()->where('type', 'App\\Notifications\\SequenceStepDueNotification')->get();

        return view('enrollments.index', compact('enrollments', 'notifications'));
    }

    public function create(Contact $contact)
    {
        // Seguridad: El cliente debe pertenecer al usuario.
        if (Auth::user()->id !== $contact->client->user_id) {
            abort(403);
        }
        $sequences = Auth::user()->sequences;
        return view('enrollments.create', compact('contact', 'sequences'));
    }

public function store(Request $request, Contact $contact)
    {
        // Seguridad: El cliente debe pertenecer al usuario.
        if (Auth::user()->id !== $contact->client->user_id) {
            abort(403);
        }

        // 1. Validamos la entrada
        $validated = $request->validate([
            'sequence_id' => 'required|exists:sequences,id'
        ]);

        // 2. BUSCAMOS la secuencia en la base de datos
        $sequence = Sequence::find($validated['sequence_id']);
        
        // 3. AHORA SÍ, comprobamos la seguridad de la secuencia
        if (Auth::user()->id !== $sequence->user_id) {
            abort(403);
        }
        
        // El resto de la lógica ya estaba bien...
        $firstStep = $sequence->steps()->orderBy('order')->first();
        
        if(!$firstStep) {
            return redirect()->back()->with('error', '¡Esa secuencia no tiene pasos!');
        }

        $contact->sequenceEnrollments()->create([
            'sequence_id' => $sequence->id,
            'user_id' => Auth::id(),
            'current_step_id' => $firstStep->id,
            'next_step_due_at' => Carbon::today()->addDays($firstStep->delay_days),
            'status' => 'active',
        ]);

        return redirect()->route('clients.show', $contact->client_id)->with('success', "¡{$contact->name} inscrito en la secuencia '{$sequence->name}'!");
    }

    public function destroy(SequenceEnrollment $enrollment)
    {
        if ($enrollment->user_id !== Auth::id()) {
            abort(403, 'Acción no autorizada.');
        }

        $enrollment->delete();

        return redirect()->route('enrollments.index')->with('success', 'Inscripción eliminada con éxito.');
    }

    public function completeStep(Request $request, SequenceEnrollment $enrollment)
    {
        if ($enrollment->user_id !== Auth::id()) {
            abort(403, 'Acción no autorizada.');
        }

        $request->validate([
            'task_id' => 'required|exists:tasks,id',
        ]);

        $task = Task::find($request->task_id);

        if (!$task || $task->taskable_id !== $enrollment->enrollable_id || $task->taskable_type !== $enrollment->enrollable_type) {
            return redirect()->back()->with('error', 'La tarea no corresponde a esta inscripción.');
        }

        // Mark the task as completed
        $task->update(['status' => 'completed']);

        // Advance the enrollment to the next step
        $currentStep = $enrollment->currentStep;
        $nextStep = $enrollment->sequence->steps()
                                         ->where('order', '>', $currentStep->order)
                                         ->first();

        if ($nextStep) {
            $enrollment->update([
                'current_step_id' => $nextStep->id,
                'next_step_due_at' => Carbon::now()->addDays($nextStep->delay_days),
            ]);
            return redirect()->back()->with('success', 'Paso completado y secuencia avanzada.');
        } else {
            $enrollment->update([
                'status' => 'completed',
                'current_step_id' => null,
                'next_step_due_at' => null,
            ]);
            return redirect()->back()->with('success', 'Paso completado y secuencia finalizada.');
        }
    }
}