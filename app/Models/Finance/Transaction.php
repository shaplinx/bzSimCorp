<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Finance\Bank;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
     * Get the bank_mutations associated with the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function mutations(): MorphMany
    {
        return $this->morphMany(BankMutation::class,"mutable");
    }

    public function getSignAttribute() {
        return $this->type === "in" ? 1 : -1;
    }

    public function getAmountAttribute() {
        return $this->mutations->sum("amount");
    }

    public function setAmountAttribute(int $amount) {
        $oldAmount =  $this->amount;
        $newAmount = abs($amount) * $this->sign;
        $mutationAmount = bcsub(strval( $oldAmount), strval($newAmount)) * -1;
        if ( $mutationAmount == 0) return;
        return $this->mutations()->create([
            "amount" => $mutationAmount,
            "date" => $this->date,
            "bank_id" => $this->bank_id,
            "description" => "Transaction's amount of $this->name changed from $oldAmount to $newAmount at $this->updated_at"
        ]);
    }
}
