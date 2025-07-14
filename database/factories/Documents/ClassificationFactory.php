<?php

namespace Database\Factories\Documents;

use App\Models\Documents\Classification;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Documents\Classification>
 */
class ClassificationFactory extends Factory
{
   protected $model = Classification::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->unique()->lexify('???')), // e.g. KEU
            'name' => $this->faker->word(),                              // e.g. Finance
            'parent_id' => null, // Use state modifiers to populate this later if needed
            'classification_separator' => $this->faker->randomElement(['.', '-', '/']),
        ];
    }

    /**
     * Indicate that the classification has a parent.
     */
    public function withParent(): static
    {
        return $this->state(function () {
            return [
                'parent_id' => Classification::factory(),
            ];
        });
    }
}
