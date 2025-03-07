<?php

namespace App\Models\Finance;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
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

        /**
     * Get all of the mutations for the Bank
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mutations(): HasMany
    {
        return $this->hasMany(BankMutation::class)
        ->whereHas("mutable")
        ->where(function($q) {
            $q->where("mutable_type",Transaction::Class);
        })
        ->groupBy('mutable_id');
        //->orWhere("mutable_type",Loan::Class);
        // ->select(
        //     'mutable_type', 
        //     'mutable_id', 
        //     'date',
        //     DB::raw('GROUP_CONCAT(description SEPARATOR "|") as description'),
        //     DB::raw('SUM(amount) as amount'), 'bank_id')
        // ->groupBy('mutable_type', 'mutable_id', 'bank_id', 'date');
    }

    public function generateReports(array | null $params = null) {
        extract($params);
        $transactions = $this->mutations()
            ->with("transaction_category")
            ->where("mutable_type", Transaction::class)
            ->when($from, function($q, $fr) {
                $q->whereDate('date', ">=", Carbon::parse($fr));
            })
            ->when($to, function($q, $t) {
                $q->whereDate('date', "<=", Carbon::parse($t));
            })->get();
        $loans = $this->mutations()
            ->where("mutable_type", Loan::class)
            ->when($from, function($q, $fr) {
                $q->whereDate('date', ">=", Carbon::parse($fr));
            })
            ->when($to, function($q, $t) {
                $q->whereDate('date', "<=", Carbon::parse($t));
            })->get();

        $table = $transactions->groupBy(function (array $item) {
            return $item->transaction_category->name;
        })
        ->push(["Loans" => $loans])
        ->all();

        return $table;
    }

    

    public function getStatsAttribute() {
        $stats = [
            "ballance" => "0",
            "moneyIn" => "0",
            "moneyOut" => "0",
            "transactions" => "0",
            "loans" => "0"
        ];

        $this->mutations->each(function (BankMutation $mutation) use (&$stats) {
            if ($mutation->amount < 0) {
                $stats["moneyOut"] = bcadd($stats["moneyOut"], $mutation->amount);
            } else {
                $stats["moneyIn"] = bcadd($stats["moneyIn"], $mutation->amount);
            }

            switch ($mutation->mutable_type) {
                case Transaction::class :
                    $stats["transactions"] = bcadd($stats["transactions"], $mutation->amount);
                    break;
                    case Loan::class :
                    $stats["loans"] = bcadd($stats["loans"], $mutation->amount);
                    break;
            }

        });

        $stats["ballance"] = bcadd($stats["moneyIn"], $stats["moneyOut"]);

        return $stats;
    }
}
