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

    public function getLongCodeAttribute() : string {
    $segments = [$this->code];
    $current = $this->parent;
    $visited = [$this->id]; 
    $depth = 1; 

    while ($current  && $depth < 3) {
        if (in_array($current->id, $visited)) {
            break; // cycle detected
        }
        array_unshift($segments, $current->code); // prepend parent code
        $separator = $current->separator ?? $separator;
        $current = $current->parent;
        $depth++;
    }

     return implode( $this->separator ?? '.', $segments);


    }

}