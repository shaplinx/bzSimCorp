<?php

namespace Database\Factories\Documents;

use App\Models\Documents\Letter;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Documents\Letter>
 */
class LetterFactory extends Factory
{
    protected $model = Letter::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userIds = User::select('id')->pluck('id')->toArray();
        return [
            'id' => Str::uuid(),
            'subject' => $this->faker->sentence(4),
            'public' => $this->faker->boolean(30), // 30% chance to be public
            'recipient' => $this->faker->optional()->name(),
            'letter_date' => $this->faker->date(),
            'voided_at' => null,
            'issued_at' => null,
            'created_by' => $this->faker->randomElement($userIds),
        ];
    }
}
