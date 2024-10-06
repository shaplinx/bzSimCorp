<?php

namespace App\Models\Finance;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        "id",
        "name",
        "hex_color"
    ];

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';


    /**
     * The users that belong to the Bank
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'bank_user', 'bank_id', 'user_id');
    }

    /**
     * Get all of the loans for the Bank
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * Get all of the transactions for the Bank
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get all of the transaction_categories for the Bank
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transaction_categories(): HasMany
    {
        return $this->hasMany(TransactionCategory::class);
    }

    public function getTransactionBallanceAttribute() {
        return $this->transactions->sum("amount");
    }

    public function getLoanBallanceAttribute() {
        return $this->loans->sum(function (Loan $loan) {
            return $loan->ballance;
        });
    }

    public function getTotalBallanceAttribute() {
        return $this->transactionBallance + $this->loanBallance;
    }
}
