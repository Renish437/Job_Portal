<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Jobe;
use App\Models\JobType;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Factories\CategoryFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    //     \App\Models\Category::factory(5)->create();
    // \App\Models\JobType::factory(5)->create();
    \App\Models\Jobe::factory(25)->create();
    }
}
