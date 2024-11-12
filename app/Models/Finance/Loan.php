<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Carbon;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "note",
        "date",
        "completed",
        "type",
        "bank_id",
    ];

    /**
     * Get the bank that owns the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }

    /**
     * Get the bank that owns the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */

    public function mutations() : MorphMany
    {
        return $this->morphMany(BankMutation::class, 'mutable');
    }

    public function principal_mutation() : MorphOne
    {
        return $this->morphOne(BankMutation::class, 'mutable')
         ->withoutGlobalScope('order')
         ->orderBy('date', 'asc')  
         ->orderBy('created_at', 'asc');
    }

    public function payment_mutations() : MorphMany
    {
        return $this->morphMany(BankMutation::class, 'mutable')
        ->whereRaw('bank_mutations.id != (
            SELECT id FROM bank_mutations 
            WHERE mutable_id = ? 
            AND mutable_type = ? 
            LIMIT 1
        )', [$this->id, get_class($this)])
        ->orderByRaw('bank_mutations.date ASC')
        ->orderByRaw('bank_mutations.created_at ASC');
    }

    public function getSignAttribute() {
        return $this->type === "in" ? 1 : -1;
    }

    public function getAmountAttribute() {
        return $this->principal_mutation->amount;
    }

    public function getPaidAmountAttribute() {
        return $this->payment_mutations->reduce(function ($sum,BankMutation $mutation) {
                return bcadd($sum,$mutation->amount);
            }, "0");
    }

    public function setAmountAttribute($amount) {
        $this->principal_mutation()->updateOrCreate([
            "mutable_type" => self::class,
            "mutable_id" => $this->id,
            "bank_id" => $this->bank_id
        ],
        [
            "amount" => abs($amount) * $this->sign,
            "date" => $this->date,
            "description" => "Principal Loan Amount Mutation for $this->name"
        ]);
        
        return $amount;
    }
    
    public function invertMutation() {
        $this->mutations()->update([
            'amount' => DB::raw('amount * -1')
        ]);
    }

    public function payLoan($amount, $date, $note) {
        $this->mutations()->create([
            "amount" => -1 * abs($amount) *$this->sign,
            "date" => Carbon::parse($date),
            "bank_id" => $this->bank_id,
            "descriptions" => $note
        ]);
    }
}
