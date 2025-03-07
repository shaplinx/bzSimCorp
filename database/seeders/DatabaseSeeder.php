<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@example.com',
        ])->roles()->updateOrCreate(['role' => 'admin']);

        \App\Models\User::factory()->create([
            'name' => 'user',
            'email' => 'user@example.com',
        ])->roles()->updateOrCreate(['role' => 'standard']);

    }
}
