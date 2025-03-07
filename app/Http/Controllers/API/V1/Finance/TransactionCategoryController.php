<?php

namespace App\Http\Controllers\API\V1\Finance;

use App\Http\Requests\API\V1\Finance\StoreTransactionCategoryRequest;
use App\Http\Requests\API\V1\Finance\UpdateTransactionCategoryRequest;
use App\Models\Finance\TransactionCategory;
use App\Http\Controllers\API\V1\ApiController;
use App\Http\Requests\API\V1\IndexRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class TransactionCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        $this->authorize('viewAny', TransactionCategory::class);
        $user = $request->user();
        $data = TransactionCategory::when(!$user->hasRole("admin"), function (Builder $query) use ($user) {
                $query->whereHas("bank", function (Builder $q) use ($user) {
                    $q->whereHas("users", function (Builder $q1) use ($user) {
                        $q1->where("users.id", $user->id);
                    });
                });
            })
            ->when($request->search, function (Builder $query, string $search) {
                $query->where(function (Builder $q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('hex_color', 'like', "%{$search}%")
                        ->orWhere('id', 'like', "%{$search}%");
                });
            })
            ->when($request->finance_transaction_type, function (Builder $query, string $type) {
                $query->where("type",$type);
            })
            ->when($request->orderBy, function (Builder $query, string $orderBy) {
                $orderBy = explode('|', $orderBy);
                $query->orderBy($orderBy[0], $orderBy[1]);
            })
            ->paginate($request->pageSize ?? 10);

        return $this->sendResponseWithPaginatedData($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionCategoryRequest $request)
    {
        $transaction_category = DB::transaction(function () use ($request) {
            $transaction_category =  TransactionCategory::create($request->all());
            return $transaction_category;
        });

        return $this->sendResponse(__("Created Successfully"), $transaction_category);
    }

    /**
     * Display the specified resource.
     */
    public function show(TransactionCategory $transaction_category)
    {
        $this->authorize('view', $transaction_category);
        return $this->sendResponse(__("Fetched Successfully"), $transaction_category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionCategoryRequest $request, TransactionCategory $transaction_category)
    {
        $transaction_category = DB::transaction(function () use ($request, $transaction_category) {
            $transaction_category->update($request->all());
            return $transaction_category;
        });

        return $this->sendResponse(__("Updated Successfully"), $transaction_category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransactionCategory $transaction_category)
    {
        $this->authorize('delete', $transaction_category);
        $transaction_category->delete();
        return $this->sendResponse(__("Deleted Successfully"));
    }
}
