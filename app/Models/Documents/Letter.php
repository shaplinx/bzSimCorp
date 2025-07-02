<?php

namespace App\Models\Documents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Letter extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string'; // UUID

    protected $fillable = [
        'id',
        'institution_id',
        'classification_id',
        'subject',
        'recipient',
        'letter_date',
        'file_path',
    ];
    protected $casts = [
        'letter_date' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
            if (empty($model->sequence_number)) {
                $model->sequence_number = $model->getNextSequenceNumber();
            }
        });
    }

    /**
     * Relationship: Letter belongs to an institution.
     */
    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    /**
     * Relationship: Letter belongs to a classification.
     */
    public function classification()
    {
        return $this->belongsTo(Classification::class);
    }

    /**
     * Relationship: Letter created by a user.
     */
    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    /**
     * Extract month from letter_date.
     */
    public function getMonthAttribute(): int
    {
        return Carbon::parse($this->letter_date)->month;
    }

    /**
     * Extract year from letter_date.
     */
    public function getYearAttribute(): int
    {
        return Carbon::parse($this->letter_date)->year;
    }

        /**
     * Extract day from letter_date.
     */
    public function getDayAttribute(): int
    {
        return Carbon::parse($this->letter_date)->day;
    }

    /**
     * Generate formatted letter number using institution template.
     */
    public function getFormattedNumberAttribute(): string
    {
        return $this->institution->formatLetterNumber([
            'sn' => $this->sequence_number,
            'classification' => $this->classification,
            'month' => $this->month,
            'year' => $this->year,
            'day' => $this->day
        ]);
    }

    /**
     * Generate sequence number.
     */
    public function getNextSequenceNumber(): string
    {

        $resetPeriod = $this->institution->reset_sn_period;
        $newDate = Carbon::parse($this->letter_date);
        // Get the latest letter
        $latestLetter = self::where('institution_id', $this->institution_id)
            ->where('classification_id', $this->classification_id)
            ->orderByDesc('letter_date')
            ->lockForUpdate()
            ->first();

        if (!$latestLetter) {
            return 1;
        } else {
            $latestDate = Carbon::parse($latestLetter->letter_date);

            $latestKey = match ($resetPeriod) {
                'd' => $latestDate->format('Y-m-d'),
                'm' => $latestDate->format('Y-m'),
                'y' => $latestDate->format('Y'),
                default => $latestDate->format('Y')
            };

            $newKey = match ($resetPeriod) {
                'd' => $newDate->format('Y-m-d'),
                'm' => $newDate->format('Y-m'),
                'y' => $newDate->format('Y'),
                default => $latestDate->format('Y')
            };

            return $latestKey === $newKey ? $latestLetter->sequence_number + 1 : 1;

        }


    }
}