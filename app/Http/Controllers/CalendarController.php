<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    /**
     * Muestra la página principal del calendario.
     */
    public function index()
    {
        return view('calendar.index');
    }

    /**
     * Devuelve los eventos en formato JSON para FullCalendar.
     */
    public function events()
    {
        $user = Auth::user();
        $events = [];

        // 1. Recolectar las Tareas
        $tasks = $user->tasks()->where('status', '!=', 'completado')->get();
        foreach ($tasks as $task) {
            $events[] = [
                'title' => 'Tarea: ' . $task->title,
                'start' => $task->due_date->format('Y-m-d'),
                'url'   => route('tasks.edit', $task), // Asumiendo que tienes un CRUD de Tareas
                'backgroundColor' => '#f59e0b', // Amarillo
                'borderColor' => '#f59e0b'
            ];
        }

        // 2. Recolectar las fechas de cierre de Deals
        $deals = $user->deals()->where('status', 'open')->get();
        foreach ($deals as $deal) {
            if ($deal->expected_close_date) {
                $events[] = [
                    'title' => 'Cierre: ' . $deal->name,
                    'start' => $deal->expected_close_date->format('Y-m-d'),
                    'url'   => route('deals.edit', $deal),
                    'backgroundColor' => '#10b981', // Verde
                    'borderColor' => '#10b981'
                ];
            }
        }

        return response()->json($events);
    }
}