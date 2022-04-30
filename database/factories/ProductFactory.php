<?php

namespace Database\Factories;

use App\Models\{Category, User};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $categorie_ids = Category::withTrashed()->pluck('_id');
        $user_ids = User::withTrashed()->pluck('_id');
        $category_id = $categorie_ids[rand(0,count($categorie_ids) - 1)];
        $user_id = $user_ids[rand(0,count($user_ids) - 1)];
        $rand_text = rand(0,1);
        return [
            'category_id' => $category_id,
            'product_code' => $this->faker->numerify('###############'),
            'product_name' => $this->faker->name(),
            'description' => ($rand_text) ? $this->faker->text('500') : null,
            'price' => $this->faker->randomDigitNotZero(),
            'created_by' => $user_id
        ];
    }
}
