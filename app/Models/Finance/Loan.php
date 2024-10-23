<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
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

    public function getSignAttribute() {
        return $this->type === "in" ? 1 : -1;
    }

    public function getLoanStatsAttribute() {
        $loanStats = [
            "total" => 0,
            "paid" => 0
        ];

        $this->mutations->each(function ($mutation) use ($loanStats) {
            if ($mutation->amount * $this->sign > 0) {
                $loanStats["total"] = bcadd(strval($loanStats["total"]),strval($mutation->amount));
            }
            else if ($mutation->amount * $this->sign < 0) {
                $loanStats["paid"] = bcadd(strval($loanStats["paid"]),strval($mutation->amount));
            }
        });

        $loanStats["percentage"] = bcdiv(strval($loanStats["paid"]),strval($loanStats["total"]));

        return $loanStats;
    }


    public function setTotalLoan($amount) {
        $total =$this->loanStats["total"];
        if ( abs($amount) ===  abs($total))  return;
        $newTotal = abs($amount) * $this->sign;

        $this->mutations()->create([
            "amount" => bcsub(strval($total), strval($newTotal)) * -1,
            "date" => $this->date,
            "name" => "Loan $this->name Amount changed from $total to $newTotal at $this->updated_at"
        ]);
    }

    public function payLoan($amount, $date, $note) {
        $this->mutations()->create([
            "amount" => -1 * abs($amount) *$this->sign,
            "date" => Carbon::parse($date),
            "name" => $note
        ]);
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

    // public function getRepaymentRatioAttribute() {
    //     return abs(bcdiv(
    //             strval($this->loanTransactions()->sum("amount")),
    //             strval($amount)));
    // }

    // public function getRepaidAmountAttribute() {
    //     return $this->loanTransactions()->sum("amount");
    // }

    // public function getMultiplierAttribute() {
    //     return $this->type === "in" ? 1 :-1;
    // }

    // public function getRemainingLoanAttribute() {
    //     return bcadd(strval($this->amunt * $this->multiplier),strval($this->repaidAmount));
    // }

}
