<?php

use App\Models\Deal;
use Carbon\Carbon;

// 1. Encontrar todos los deals ganados
$wonDeals = Deal::where('status', 'won')->with('tasks')->get();

$timeDifferences = [];

// 2. Iterar sobre cada deal ganado
foreach ($wonDeals as $deal) {
    // La fecha en que se ganó el deal
    $wonDate = $deal->updated_at;

    // 3. Encontrar la primera tarea creada DESPUÉS de que se ganó el deal
    $firstTask = $deal->tasks()
        ->where('created_at', '>=', $wonDate)
        ->orderBy('created_at', 'asc')
        ->first();

    // 4. Si existe una tarea, calcular la diferencia de tiempo
    if ($firstTask) {
        $taskCreationDate = $firstTask->created_at;
        $differenceInSeconds = $taskCreationDate->diffInSeconds($wonDate);
        $timeDifferences[] = $differenceInSeconds;
    }
}

echo "--- Análisis de Transición Venta -> Operación ---
";

if (count($timeDifferences) > 0) {
    // 5. Calcular el promedio
    $averageSeconds = array_sum($timeDifferences) / count($timeDifferences);
    $averageHuman = Carbon::now()->subSeconds($averageSeconds)->diffForHumans(null, true);

    echo "Se analizaron " . count($timeDifferences) . " deals ganados con tareas subsecuentes.\n";
    echo "El tiempo promedio entre ganar un deal y crear la primera tarea es de: " . $averageHuman . "\n";
} else {
    echo "No se encontraron deals ganados que tuvieran tareas creadas posteriormente.\n";
    echo "Esto puede indicar una de dos cosas:\n";
    echo "1. No se han ganado deals recientemente.\n";
    echo "2. No se están creando tareas para los deals ganados (¡la integración no está ocurriendo!).\n";
}

?>