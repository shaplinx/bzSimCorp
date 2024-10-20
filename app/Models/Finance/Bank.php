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
        "hex_color",

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
    public function transactionCategories(): HasMany
    {
        return $this->hasMany(TransactionCategory::class);
    }

    public function transactionMutations()
    {
        return $this->hasManyThrough(BankMutation::class, Transaction::class, 'bank_id', 'mutator_id')->where("mutator_type",Transaction::class);
    }

    public function loanMutations()
    {
        return $this->hasManyThrough(BankMutation::class, Loan::class, 'bank_id', 'mutator_id')->where("mutator_type",Loan::class);
    }

    public function getStatsAtribute() {
        $stats = [
            "ballance" => 0,
            "moneyIn" => 0,
            "moneyOut" => 0,
            "transactions" => 0,
            "loans" => 0
        ];

        $this->transactions->each(function (Transaction $transaction) {
            $stats["transactions"] = bcadd(strval($stats["transactions"]),strval($transaction->amount));
            if ($transaction->sign === 1) {
                $stats["moneyIn"] = bcadd(strval($stats["moneyIn"]),strval($transaction->amount));
            } else {
                $stats["moneyOut"] = bcsub(strval($stats["moneyOut"]),strval($transaction->amount));
            }
        });

        $this->loans->each(function (Transaction $transaction) {
            $stats["loans"] = bcadd(strval($stats["loans"]),strval($transaction->amount));
            if ($transaction->sign === 1) {
                $stats["moneyIn"] = bcadd(strval($stats["moneyIn"]),strval($transaction->amount));
            } else {
                $stats["moneyOut"] = bcsub(strval($stats["moneyOut"]),strval($transaction->amount));
            }
        });

        $stats["ballance"] = bcadd(strval($stats["transactions"]),strval($stats["loans"] ));

        return $stats;
    }
}
