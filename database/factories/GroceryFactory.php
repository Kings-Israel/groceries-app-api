<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Grocery>
 */
class GroceryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => \App\Models\Category::factory(),
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'image' => $this->faker->imageUrl(640, 480, 'food', true),
            'price' => $this->faker->randomFloat(2, 1, 100),
            'unit' => $this->faker->randomElement(['kg', 'litre', 'piece']),
            'stock' => $this->faker->numberBetween(0, 100),
            'is_available' => $this->faker->boolean(80), // 80% chance of being true
        ];
    }
}
