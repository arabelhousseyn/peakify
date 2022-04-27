<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
    public function definition()
    {
        return [
            'full_name' => $this->faker->name(),
            'phone' => $this->faker->numerify('##########'),
            'email' => $this->faker->safeEmail,
            'city' => $this->faker->city,
            'address' => $this->faker->address
        ];
    }
}
