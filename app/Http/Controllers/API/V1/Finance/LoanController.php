<?php

namespace App\Http\Controllers\API\V1\Finance;

use App\Http\Requests\API\V1\Finance\StoreLoanRequest;
use App\Http\Requests\API\V1\Finance\UpdateLoanRequest;
use App\Models\Finance\Loan;
use App\Http\Controllers\API\V1\ApiController;
use App\Http\Requests\API\V1\IndexRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class LoanController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        $this->authorize('viewAny', Loan::class);
        $user = $request->user();
        $data = Loan::
        withSum('principal_mutation as loan_amount', 'amount')
        ->withSum('payment_mutations as paid_loan_amount', 'amount')
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
        })->when($request->completed, function (Builder $query, string $completed) {
            $query->where("completed", $completed);
        })->when($request->orderBy, function (Builder $query, string $orderBy) {
            $orderBy = explode('|', $orderBy);
            $query->orderBy($orderBy[0], $orderBy[1]);
        })->paginate($request->pageSize ?? 10);

        return $this->sendResponseWithPaginatedData($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLoanRequest $request)
    {
        $loan = DB::transaction(function () use ($request) {
            $loan =  Loan::create($request->all());
            $loan->amount = $request->amount;
            return $loan->refresh();
        });

        return $this->sendResponse(__("Created Successfully"), $loan->append("amount"));
    }

    /**
     * Display the specified resource.
     */
    public function show(Loan $loan)
    {
        $this->authorize('view', $loan);
        return $this->sendResponse(__("Fetched Successfully"), $loan->append("amount","paidAmount" ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLoanRequest $request, Loan $loan)
    {
        $loan = DB::transaction(function () use ($request, $loan) {
            $isTypeChanged = $loan->type != $request->type;
            $loan->update($request->all());
            if ($isTypeChanged) $loan->invertMutation();
            $loan->amount = $request->amount;
            $loan->refresh();
            return $loan;
        });

        return $this->sendResponse(__("Updated Successfully"), $loan->append("amount", "paidAmount"));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Loan $loan)
    {
        $this->authorize('delete', $loan);
        DB::transaction(function () {
            $loan->mutations()->delete();
            $loan->delete();
        });
        return $this->sendResponse(__("Deleted Successfully"));
    }

    public function payLoan(Request $request) {
        $request->validate([
            "amount" =>  ["required", "numeric", "gt:0"],
            "date" => ["required", "date"],
            "description"  =>  ["required", "string", "max:1000", "min:3"]
        ]);
    }
}
