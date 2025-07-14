<?php

namespace Database\Seeders;

use App\Models\Documents\Classification;
use App\Models\Documents\Institution;
use App\Models\Documents\Letter;
use Illuminate\Database\Seeder;

class LetterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $institution = Institution::factory()->create();
        $classifications = Classification::factory()->count(3)->create();

        Letter::factory()
            ->count(10)
            ->for($institution)
            ->for($classifications->random())
            ->create();
    }
}
