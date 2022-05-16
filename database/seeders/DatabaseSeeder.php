<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{Category, City, Client, Product, User};
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(1)->create();
        City::factory(20)->create();
        Client::factory(20)->create();
        Category::factory(6)->create();
        Product::factory(10000)->create();
    }
}
