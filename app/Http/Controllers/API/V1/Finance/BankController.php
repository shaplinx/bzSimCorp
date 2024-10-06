<?php

namespace App\Http\Controllers\API\V1\Finance;

use App\Http\Requests\API\V1\Finance\StoreBankRequest;
use App\Http\Requests\API\V1\Finance\UpdateBankRequest;
use App\Models\Finance\Bank;
use App\Http\Controllers\API\V1\ApiController;
use App\Http\Requests\API\V1\IndexRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;


class BankController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        $this->authorize('viewAny', Bank::class);
        $user = $request->user();
        $data = Bank::when(!$user->hasRole("admin"), function (Builder $query) use ($user) {
                $query->whereHas("users", function (Builder $q) use ($user) {
                    $q->where("users.id", $user->id);
                });
            })
            ->with(["transactions", "loans.loanTransactions"])
            ->when($request->search, function (Builder $query, string $search) {
                $query->where(function (Builder $q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('hex_color', 'like', "%{$search}%")
                        ->orWhere('id', 'like', "%{$search}%");
                });
            })
            ->when($request->orderBy, function (Builder $query, string $orderBy) {
                $orderBy = explode('|', $orderBy);
                $query->orderBy($orderBy[0], $orderBy[1]);
            })
            ->paginate($request->pageSize ?? 10);

        $data->getCollection()->transform(function ($bank) {
            return $bank->append(["transaction_ballance", "loan_ballance", "total_ballance"]);
        });

        return $this->sendResponseWithPaginatedData($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBankRequest $request)
    {
        $bank = DB::transaction(function () use ($request) {
            $bank =  Bank::create($request->all());
            $bank->users()->sync($request->users);
            return $bank->refresh();
        });

        return $this->sendResponse(__("Created Successfully"), $bank);
    }

    /**
     * Display the specified resource.
     */
    public function show(Bank $bank)
    {
        $this->authorize('view', $bank);
        return $this->sendResponse(__("Fetched Successfully"), $bank->append(["transaction_ballance", "loan_ballance", "total_ballance"]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBankRequest $request, Bank $bank)
    {
        $bank = DB::transaction(function () use ($request, $bank) {
            $bank->update($request->all());
            $bank->users()->sync($request->users);
            return $bank->refresh();
        });

        return $this->sendResponse(__("Updated Successfully"), $bank);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bank $bank)
    {
        $this->authorize('delete', $bank);
        $bank->delete();
        return $this->sendResponse(__("Deleted Successfully"));
    }
}
