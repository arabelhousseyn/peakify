<?php

namespace Database\Factories;

use App\Models\City;
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
        $cities_ids = City::withTrashed()->get()->pluck('_id');


        return [
            'full_name' => $this->faker->name(),
            'phone' => $this->faker->numerify('##########'),
            'email' => $this->faker->safeEmail,
            'city_id' => $cities_ids[rand(0,count($cities_ids ) - 1)],
            'address' => $this->faker->address
        ];
    }
}
