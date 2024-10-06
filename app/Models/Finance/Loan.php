<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "note",
        "date",
        "amount",
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
     * Get all of the loanTransaction for the Loan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function loanTransactions(): HasMany
    {
        return $this->hasMany(LoanTransaction::class);
    }

    // public function adjustFirstLoanTransaction()
    // {
    //     $loanTransaction = [
    //         "date" => $this->date,
    //         "note" => $this->title,
    //         "amount" => $this->type === "in" ? abs($this->amount) : abs($this->amount) * -1,
    //     ];
    //     $firstLoanTransaction = $this->loanTransactions()->first();
    //     !$firstLoanTransaction ? $this->loanTransactions()->create($loanTransaction) : $firstLoanTransaction->update($loanTransaction);
    //     return $firstLoanTransaction;
    // }

    public function getRepaymentRatioAttribute() {
        return abs(bcdiv(
                strval($this->loanTransactions()->sum("amount")),
                strval($amount)));
    }

    public function getRepaidAmountAttribute() {
        return $this->loanTransactions()->sum("amount");
    }

    public function getMultiplierAttribute() {
        return $this->type === "in" ? 1 :-1;
    }

    public function getRemainingLoanAttribute() {
        return bcadd(strval($this->amunt * $this->multiplier),strval($this->repaidAmount));
    }

}
