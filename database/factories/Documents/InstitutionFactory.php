<?php

namespace Database\Factories\Documents;

use App\Models\Documents\Institution;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Documents\Institution>
 */
class InstitutionFactory extends Factory
{
   protected $model = Institution::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $code = strtoupper($this->faker->unique()->bothify('SMAN###'));
        return [
            'code' => $code,
            'name' => "State Senior High School " . preg_replace('/\D/', '', $code),
            'reset_sn_period' => $this->faker->randomElement(['d', 'm', 'y']),
            'sn_template' => '[SN]/[INSTANCE]/[CLASSIFICATION]/[MONTH_ROMAN]/[YEAR]',
        ];
    }
}
