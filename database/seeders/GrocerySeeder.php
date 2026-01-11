<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GrocerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Grocery::factory()->create([
            'category_id' => 1,
            'name' => 'Apple',
            'description' => 'Fresh red apples',
            'image' => 'groceries/apple.jpg',
            'price' => 3.50,
            'unit' => 'kg',
            'stock' => 100,
            'is_available' => true,
        ]);

        \App\Models\Grocery::factory()->create([
            'category_id' => 2,
            'name' => 'Milk',
            'description' => 'Organic whole milk',
            'image' => 'groceries/milk.jpg',
            'price' => 1.20,
            'unit' => 'litre',
            'stock' => 200,
            'is_available' => true,
        ]);

        \App\Models\Grocery::factory()->create([
            'category_id' => 3,
            'name' => 'Bread',
            'description' => 'Freshly baked bread',
            'image' => 'groceries/bread.jpg',
            'price' => 2.00,
            'unit' => 'piece',
            'stock' => 150,
            'is_available' => true,
        ]);
    }
}
