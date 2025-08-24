<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Client;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
{
    return [
        'client_id' => Client::factory(),
        'name' => fake()->name,
        'email' => fake()->unique()->safeEmail,
        'phone' => fake()->phoneNumber,
        'position' => fake()->jobTitle,
        'notes' => fake()->sentence,
        'active' => 1,
    ];
}
}
