<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Category::factory()->create([
            'name' => 'Fruits & Vegetables',
            'image' => 'categories/fruits_vegetables.jpg',
            'is_active' => true,
        ]);

        \App\Models\Category::factory()->create([
            'name' => 'Dairy Products',
            'image' => 'categories/dairy_products.jpg',
            'is_active' => true,
        ]);

        \App\Models\Category::factory()->create([
            'name' => 'Bakery Items',
            'image' => 'categories/bakery_items.jpg',
            'is_active' => true,
        ]);
    }
}
