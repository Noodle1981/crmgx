<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
{
    return [
        'name' => fake()->company,
        'email' => fake()->unique()->companyEmail,
        'phone' => fake()->phoneNumber,
        'company' => fake()->company,
        'notes' => fake()->paragraph,
        'active' => 1,
        'user_id' => User::factory(), // Asocia un nuevo usuario o uno existente
    ];
}
}
