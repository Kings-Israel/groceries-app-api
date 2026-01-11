<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Admin::factory()->create([
            'name' => 'Admin One',
            'email' => 'admin@one.com',
            'password' => bcrypt('password'),
        ]);

        \App\Models\Admin::factory()->create([
            'name' => 'Admin Two',
            'email' => 'admin@two.com',
            'password' => bcrypt('password'),
        ]);
    }
}
