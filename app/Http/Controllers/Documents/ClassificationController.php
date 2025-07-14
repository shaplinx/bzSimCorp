<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Models\Documents\Classification;
use App\Http\Requests\Documents\StoreClassificationRequest;
use App\Http\Requests\Documents\UpdateClassificationRequest;
use App\Http\Requests\IndexRequest;
use Illuminate\Database\Eloquent\Builder;

class ClassificationController extends Controller
{
    public function index(IndexRequest $request)
    {
        return $this->sendResponseWithPaginatedData(
            Classification::with("parent")->when($request->search, function (Builder $query, string $search) {
                    $query->where(function (Builder $q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('code', 'like', "%{$search}%");
                    });
                })
                ->when($request->dateAfter, function (Builder $query, string $dateAfter) {
                    $query->whereDate('created_at', '>=', $dateAfter);
                })
                ->when($request->dateBefore, function (Builder $query, $dateBefore) {
                    $query->whereDate('created_at', '<=', $dateBefore);
                })
                ->when($request->orderBy, function (Builder $query, array $orderBy) {
                    $query->orderBy($orderBy['column'] ?? "created_at", $orderBy['direction'] ?? "DESC");
                })
                ->paginate($request->pageSize ?? 10)
        );
    }

    public function store(StoreClassificationRequest $request)
    {
        $classification = Classification::create($request->validated());

        return $this->sendResponse(__('Created Successfully'), $classification->load("parent"));
    }

    public function show(Classification $classification)
    {
        return $this->sendResponse(__('Fetched Successfully'), $classification->load("parent"));
    }

    public function update(UpdateClassificationRequest $request, Classification $classification)
    {
        $classification->update($request->validated());

        return $this->sendResponse(__('Updated Successfully'), $classification->load("parent"));
    }

    public function destroy(Classification $classification)
    {
        $classification->delete();

        return $this->sendResponse(__('Deleted Successfully'));
    }
}
