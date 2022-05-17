<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Shipper;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shipper>
 */
class ShipperFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $genders = ['M','F'];
        $types = ['C','P'];
        return [
            'full_name' => $this->faker->name($genders[rand(0,count($genders) - 1)]),
            'phone' => $this->faker->numerify('##########'),
            'email' => $this->faker->email,
            'type' => $types[rand(0,count($types) - 1)]
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Shipper $shipper){
            $cities = City::all()->pluck('_id');
            collect($cities)->map(function ($city) use ($shipper){
                $shipper->cities()->create([
                    'city_id' => $city,
                    'price' => $this->faker->numberBetween(100,1000)
                ]) ;
            });
        });
    }
}
