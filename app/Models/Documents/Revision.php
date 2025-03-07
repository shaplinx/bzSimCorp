<?php

namespace App\Models\Documents;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Revision extends Pivot 
{

    protected $table = 'revisions';
    public $timestamps = true;
    protected $fillable = ['revising_id', 'revised_id'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }
}