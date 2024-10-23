<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Builder;


class BankMutation extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable =[
        "description",
        "date",
        "amount",
        "bank_id"
    ];

    public static function booted() {
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('date', 'desc')  
                    ->orderBy('created_at', 'desc'); 
        });

    }

     /**
     * Get the parent mutable model.
     */
    public function mutable(): MorphTo
    {
        return $this->morphTo();
    }
}
