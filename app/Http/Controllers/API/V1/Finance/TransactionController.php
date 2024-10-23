<?php

namespace App\Http\Controllers\API\V1\Finance;

use App\Http\Requests\API\V1\Finance\StoreTransactionRequest;
use App\Http\Requests\API\V1\Finance\UpdateTransactionRequest;
use App\Models\Finance\Transaction;
use App\Http\Controllers\API\V1\ApiController;
use App\Http\Requests\API\V1\IndexRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class TransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        $this->authorize('viewAny', Transaction::class);
        $user = $request->user();
        $data = Transaction::with(["mutations", "bank"])
        ->whereHas("bank", function (Builder $bankBuilder) use ($user, $request) {
            $bankBuilder
                ->when($request->finance_bank, function (Builder $query, string $bank) {
                    $query->where("bank.id", $bank);
                })
                ->whereHas("users", function (Builder $q1) use ($user) {
                    $q1->when(!$user->hasRole("admin"), function (Builder $q2) use ($user) {
                        $q2->where("users.id", $user->id);
                    });
                });
        })->when($request->search, function (Builder $query, string $search) {
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('note', 'like', "%{$search}%")
                    ->orWhere('amount', 'like', "%{$search}%");
            });
        })->when($request->finance_transaction_category, function (Builder $query, string $category) {
            $query->where("transaction_category_id", $category);
        })->when($request->orderBy, function (Builder $query, string $orderBy) {
            $orderBy = explode('|', $orderBy);
            $query->orderBy($orderBy[0], $orderBy[1]);
        })->paginate($request->pageSize ?? 10);

        $data->getCollection()->transform(function ($transaction) {
            return $transaction->append(["amount"]);
        });
        return $this->sendResponseWithPaginatedData($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        $transaction = DB::transaction(function () use ($request) {
            $transaction =  Transaction::create($request->all());
            $transaction->transaction_category()->associate(intval($request->transaction_category_id));
            $transaction->amount = $request->amount;
            return $transaction;
        });

        return $this->sendResponse(__("Created Successfully"), $transaction->append("amount"));
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $this->authorize('view', $transaction);
        return $this->sendResponse(__("Fetched Successfully"), $transaction->append("amount"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $transaction = DB::transaction(function () use ($request, $transaction) {
            $transaction->update($request->all());
            $transaction->transaction_category()->associate(intval($request->transaction_category_id));
            $transaction->amount = $request->amount;
            $transaction->refresh();
            return $transaction;
        });

        return $this->sendResponse(__("Updated Successfully"), $transaction->append("amount"));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);
        $transaction->delete();
        return $this->sendResponse(__("Deleted Successfully"));
    }
}
