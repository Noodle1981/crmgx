<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
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
}