<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\DealStage;
use App\Models\Establishment; // <-- Importar el nuevo modelo
use App\Models\Lead;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Solo crear usuarios admin y user
        \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'is_admin' => true,
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Normal User',
            'email' => 'user@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'is_admin' => false,
        ]);
    }
}