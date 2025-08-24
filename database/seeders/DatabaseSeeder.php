<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Contact;
use App\Models\Deal;
use App\Models\DealStage;
use App\Models\Lead;
use App\Models\Task;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Crear un usuario principal para que puedas iniciar sesión
        $mainUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        // 2. Crear las etapas del pipeline de ventas (deals)
        $stage1 = DealStage::create(['name' => 'Contacto Inicial', 'order' => 1]);
        $stage2 = DealStage::create(['name' => 'Propuesta Enviada', 'order' => 2]);
        $stage3 = DealStage::create(['name' => 'Negociación', 'order' => 3]);

        // 3. Crear datos asociados a nuestro usuario principal
        $clients = Client::factory(10)
            ->has(Contact::factory()->count(2)) // Cada cliente tiene 2 contactos
            ->has(
                Deal::factory()->count(1)->state(function (array $attributes, Client $client) use ($stage1) {
                    return ['user_id' => $client->user_id, 'deal_stage_id' => $stage1->id];
                })
            ) // Y un deal
            ->create(['user_id' => $mainUser->id]);

        // 4. Crear algunos leads sueltos
        Lead::factory(5)->create(['user_id' => $mainUser->id]);

        // 5. Crear tareas para algunos de los recursos creados
        foreach ($clients->random(3) as $client) {
            Task::factory()->create([
                'user_id' => $mainUser->id,
                'taskable_id' => $client->id,
                'taskable_type' => Client::class,
                'due_date' => now()->addDays(rand(1, 30)),
            ]);
        }

        $deals = Deal::all();
        foreach ($deals->random(3) as $deal) {
            Task::factory()->create([
                'user_id' => $mainUser->id,
                'taskable_id' => $deal->id,
                'taskable_type' => Deal::class,
                'due_date' => now()->addDays(rand(1, 30)),
            ]);
        }
    }
}
