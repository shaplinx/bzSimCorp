<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Finance\Bank;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

use Illuminate\Support\Facades\Log;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "note",
        "date",
        "type",
        "transaction_category_id",
        "bank_id"
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
     * Get the category that owns the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transaction_category(): BelongsTo
    {
        return $this->belongsTo(TransactionCategory::class,"transaction_category_id");
    }

    /**
     * Get the bank_mutation associated with the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function mutation(): MorphOne
    {
        return $this->morphOne(BankMutation::class,"mutable");
    }

        /**
     * Get the unused_mutations associated with the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function unused_mutations(): MorphMany
    {
        return $this->morphMany(BankMutation::class,"mutable")->whereNot('id', $this->mutation?->id);
    }

    public function getSignAttribute() {
        return $this->type === "in" ? 1 : -1;
    }

    public function getAmountAttribute() {
        return $this->mutation?->amount ?? "0";
    }

    public function setAmountAttribute(int $amount) {
        $oldAmount =  $this->amount;
        $newAmount = abs($amount) * $this->sign;
        if (  $oldAmount == $newAmount) return;
        return $this->mutation()->updateOrCreate([
            "mutable_type" => self::class,
            "mutable_id" => $this->id
        ],[
            "amount" => $newAmount,
            "date" => $this->date,
            "bank_id" => $this->bank_id,
            "description" => "Mutation for Transaction $this->name"
        ]);
    }
}
