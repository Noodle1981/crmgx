<?php

// Este script actualiza al usuario con ID 1 para que sea administrador
// y le asigna una tasa de comisión del 10%.

use App\Models\User;

$user = User::find(1);

if ($user) {
    $user->is_admin = true;
    $user->settings = ['commission_rate' => 10];
    $user->save();

    echo "Usuario con ID 1 actualizado exitosamente.\n";
    echo "Es administrador: " . ($user->is_admin ? 'Si' : 'No') . "\n";
    echo "Tasa de comisión: " . ($user->settings['commission_rate'] ?? 'N/A') . "%\n";
} else {
    echo "No se encontró al usuario con ID 1.\n";
}


