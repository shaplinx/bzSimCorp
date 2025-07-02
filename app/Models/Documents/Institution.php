<?php

namespace App\Models\Documents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Institution extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'reset_sn_period', // e.g. "d", "m", "Y".
        'sn_template',     // e.g. [SN]/[INSTANCE]/[CLASSIFICATION]/[MONTH_ROMAN]/[YEAR]
    ];

    /**
     * Relationship: An institution has many letters.
     */
    public function letters()
    {
        return $this->hasMany(Letter::class);
    }

    /**
     * Parse reset_sn_period like "1M3D6H" to DateInterval
     */
    public function getResetPeriodInterval(): \DateInterval
    {
        $pattern = '/(?:(\d+)Y)?(?:(\d+)M)?(?:(\d+)D)?(?:(\d+)H)?/i';
        if (!preg_match($pattern, strtoupper($this->reset_sn_period), $matches)) {
            return new \DateInterval("P1Y");
        }

        $years = isset($matches[1]) ? (int) $matches[1] : 0;
        $months = isset($matches[2]) ? (int) $matches[2] : 0;
        $days = isset($matches[3]) ? (int) $matches[3] : 0;
        $hours = isset($matches[4]) ? (int) $matches[4] : 0;

        // Build interval manually
        $interval = new \DateInterval("P{$years}Y{$months}M{$days}D");

        if ($hours > 0) {
            $interval->h = $hours;
            $interval->invert = 0;
        }

        return $interval;
    }

    /**
     * Format letter number using the institution's template.
     */
    public function formatLetterNumber(array $params): string
    {
        $replacements = [
            '[SN]' => str_pad($params['sn'], 3, '0', STR_PAD_LEFT),
            '[INSTANCE]' => $this->code,
            '[CLASSIFICATION]' => $params['classification']->code,
            '[FULL_CLASSIFICATION]' => $params['classification']->LongCode,
            '[DAY]' => $params['day'],
            '[DAY_ROMAN]' => $this->toRoman($params['day']),
            '[MONTH]' => $params['month'],
            '[MONTH_ROMAN]' => $this->toRoman($params['month']),
            '[YEAR]' => $params['year'],
            '[YEAR_ROMAN]' => $this->toRoman($params['year']),

        ];

        return str_replace(array_keys($replacements), array_values($replacements), $this->sn_template);
    }

    /**
     * Convert any integer to Roman numeral.
     */
    public function toRoman(int $number): string
    {
        if ($number <= 0 || !$number)
            return '';

        $map = [
            'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1,
        ];

        $roman = '';
        foreach ($map as $romanChar => $value) {
            while ($number >= $value) {
                $roman .= $romanChar;
                $number -= $value;
            }
        }

        return $roman;
    }
}