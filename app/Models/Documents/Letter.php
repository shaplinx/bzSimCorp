<?php

namespace App\Models\Documents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Letter extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string'; // UUID

    protected $appends = ['formatted_number', "status"];

    protected $fillable = [
        'id',
        'institution_id',
        'classification_id',
        'subject',
        'recipient',
        'letter_date',
        'file_path',
        "voided_at",
        "issued_at",
        "public",
    ];
    protected $casts = [
        'letter_date' => 'datetime',
        'issued_at' => 'datetime',
        'voided_at' => 'datetime',
        'public' => 'boolean',
    ];

    protected static function booted()
    {
        parent::booted();

        static::deleting(function ($model) {
            if ($model->status !== "draft") abort(400, "Cannot delete issued or voided letter");
        });

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }

            if (empty($model->created_by)) {
                $model->created_by = Auth::id();
            }
            if (empty($model->sn) && ($model->status === 'issued')) {
                $model->sn = $model->getNextSequenceNumber();
            }
        });

        static::updating(function ($model) {
            $original = $model->getOriginal();
            $wasIssued = $original['issued_at'] !== null;
            $wasVoided = $original['voided_at'] !== null;
            $isVoiding = $model->voided_at && !$wasVoided;
            $isUnvoiding = !$model->voided_at && $wasVoided;

            $originalStatus = $model->getOriginal('status'); // virtual field (accessor)

            // ❌ 1. Prevent unvoiding
            if ($isUnvoiding) {
                abort(400, 'Cannot unvoid a letter once it has been voided.');
            }

            // ❌ 2. Prevent voiding a draft
            if (!$wasIssued && $model->voided_at) {
                abort(400, 'Cannot void a letter that has not been issued.');
            }

            // ✅ 3. Allow issuing a draft → assign sn and issued_at
            if ($originalStatus === 'draft' && $model->issued_at && !$model->sn) {

                $model->sn = $model->getNextSequenceNumber();
            }

            // ❌ 4. Prevent any mutation to voided letter.
            if ($wasVoided) {
                abort(400, 'Cannot update a voided letter.');
            }

            // ❌ 5. Prevent any mutation to issued letter unless voiding
            if ($wasIssued) {
                $dirty = collect($model->getDirty())->except(['updated_at'])->keys()->toArray();

                if ($isVoiding) {
                    $allowedKeys = ['voided_at'];
                    $unexpectedChanges = array_diff($dirty, $allowedKeys);
                    if (!empty($unexpectedChanges)) {
                        abort(400, 'Only voiding is allowed. Cannot modify other fields on issued letter.');
                    }
                } else abort(400, 'Cannot update an issued letter unless voiding.');
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
     * Extract status.
     */
    public function getStatusAttribute(): string
    {
        if ($this->voided_at) {
            return 'void';
        }

        if ($this->issued_at) {
            return 'issued';
        }

        return 'draft';
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
        if (!$this->sn) return "";
        return $this->institution->formatLetterNumber([
            'sn' => $this->sn,
            'classification' => $this->classification,
            'month' => $this->month,
            'year' => $this->year,
            'day' => $this->day
        ]);
    }

    /**
     * Generate sequence number.
     */
    public function getNextSequenceNumber(): int | null
    {
        $institution = $this->institution;
        $resetPeriod = strtolower($institution->reset_sn_period); // 'd', 'm', 'y'
        $date = Carbon::parse($this->letter_date);

        $query = self::where('institution_id', $this->institution_id)
            ->where('classification_id', $this->classification_id)
            ->lockForUpdate();

        // Apply reset interval condition
        switch ($resetPeriod) {
            case 'd':
                $query->whereDate('letter_date', $date->toDateString());
                break;
            case 'm':
                $query->whereYear('letter_date', $date->year)
                    ->whereMonth('letter_date', $date->month);
                break;
            case 'y':
            default:
                $query->whereYear('letter_date', $date->year);
                break;
        }

        $max = $query->max('sn');

        return $max ? $max + 1 : 1;
    }
}
