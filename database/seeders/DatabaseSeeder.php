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
        // 1. Crear usuarios de prueba
        $adminUser = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        $normalUser = User::factory()->create([
            'name' => 'Normal User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);
        
        // 2. Crear las etapas del pipeline de ventas
        $stage1 = DealStage::create(['name' => 'Contacto Inicial', 'order' => 1]);
        $stage2 = DealStage::create(['name' => 'Propuesta Enviada', 'order' => 2]);
        $stage3 = DealStage::create(['name' => 'Negociación', 'order' => 3]);
        $stageWon = DealStage::create(['name' => 'Ganado', 'order' => 4]);

        // 3. Crear una estructura de datos realista y conectada
        echo "Creando Clientes, Establecimientos y Contactos...\n";

        // Creamos 20 clientes, cada uno asignado a uno de los usuarios.
        Client::factory(20)->create([
            'user_id' => rand(0, 1) ? $adminUser->id : $normalUser->id
        ])->each(function ($client) {
            
            // Para cada cliente, creamos entre 1 y 3 establecimientos.
            $establishments = Establishment::factory(rand(1, 3))->create([
                'client_id' => $client->id,
            ]);

            // Creamos 1 o 2 contactos corporativos (sin establecimiento asignado).
            Contact::factory(rand(1, 2))->create([
                'client_id' => $client->id,
                'establishment_id' => null, // Explícitamente nulo
            ]);

            // Para cada establecimiento, creamos 1 contacto específico de esa sede.
            foreach ($establishments as $establishment) {
                Contact::factory()->create([
                    'client_id' => $client->id, // El contacto pertenece al cliente
                    'establishment_id' => $establishment->id, // Y está asignado a esta sede
                ]);
            }
        });

        // 4. Crear Deals para algunos clientes
        echo "Creando Deals...\n";
        $allClients = Client::all();
        foreach ($allClients->random(10) as $client) {
            Deal::factory()->create([
                'user_id' => $client->user_id,
                'client_id' => $client->id,
                'deal_stage_id' => $stage1->id,
            ]);
        }
        
        // 5. Crear leads sueltos
        Lead::factory(15)->create(['user_id' => $normalUser->id]);

        // 6. Crear tareas asociadas
        echo "Creando Tareas...\n";
        Task::factory(10)->create([
            'user_id' => $adminUser->id,
            'taskable_id' => $allClients->random()->id,
            'taskable_type' => Client::class,
        ]);
        
        Task::factory(5)->create([
            'user_id' => $normalUser->id,
            'taskable_id' => Deal::all()->random()->id,
            'taskable_type' => Deal::class,
        ]);

        echo "Seeding completado!\n";
    }
}