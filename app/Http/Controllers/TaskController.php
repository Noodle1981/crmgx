<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Auth::user()->tasks()
            ->with('taskable') // Carga la relación polimórfica
            ->orderBy('due_date', 'asc')
            ->paginate(15);

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        // Por seguridad, nos aseguramos de que un usuario no pueda ver tareas de otro.
        // Esta lógica puede ser más compleja si hay roles de administrador, etc.
        if ($task->user_id !== auth()->id()) {
            abort(403, 'Acceso no autorizado');
        }

        return view('tasks.edit', compact('task'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403, 'Acceso no autorizado');
        }

        $task->delete();

        return redirect()->route('tasks.index')->with('success', '¡Tarea eliminada con éxito!');
    }
}