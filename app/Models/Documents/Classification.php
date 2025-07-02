<?php

namespace App\Models\Documents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classification extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', // e.g. KEU
        'name', // e.g. Finance
        'classification_separator',
        'parent_id'
    ];

    /**
     * Relationship: A classification may be used in many letters.
     */
    public function letters()
    {
        return $this->hasMany(Letter::class);
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function getLongCodeAttribute(): string
    {
        //$segments = [$this->code];
        $codes = $this->code;
        $current = $this->parent;
        $visited = [$this->id];

        while ($current) {
            if (in_array($current->id, $visited)) {
                break; // cycle detected
            }
            $codes = $codes . $current->classification_separator . $current->code;
            $current = $current->parent;
        }

        return $codes;

    }

}