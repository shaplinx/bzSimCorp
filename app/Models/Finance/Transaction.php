<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Finance\Bank;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function bank_mutation(): MorphMany
    {
        return $this->morphMany(BankMutation::class);
    }

    public function getSignAttribute() {
        return $this->type === "in" ? 1 : -1;
    }

    public function getAmountAttribute() {
        return $this->mutation->sum("amount");
    }

    public function setAmount(int $amount) {
        $newAmount = abs($amount) * $this->sign;
        if ($this->amount === $newAmount) return;
        $this->bank_mutation()->updateOrCreate([
            "amount" =>bcsub(strval($this->amount), strval($newAmount)) * -1,
            "date" => $this->date,
            "name" => "Transaction's amount of $this->name changed from $this->amount to $newAmount at $this->updated_at"
        ]);
    }
}
