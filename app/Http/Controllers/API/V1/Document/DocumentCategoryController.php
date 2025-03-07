<?php

namespace App\Http\Controllers\API\V1\Finance;

use App\Http\Requests\API\V1\Finance\StoreDocumentCategoryRequest;
use App\Http\Requests\API\V1\Finance\UpdateDocumentCategoryRequest;
use App\Models\Finance\DocumentCategory;
use App\Http\Controllers\API\V1\ApiController;
use App\Http\Requests\API\V1\IndexRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;


class DocumentCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {

        $this->authorize('viewAny', DocumentCategory::class);
        $data = DocumentCategory::when($request->search, function (Builder $query, string $search) {
                $query->scopeSearch(  "name","slug","description");
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
    public function store(StoreDocumentCategoryRequest $request)
    {
        $DocumentCategory = DB::transaction(function () use ($request) {
            return DocumentCategory::create($request->all());
        });

        return $this->sendResponse(__("Created Successfully"), $DocumentCategory);
    }

    /**
     * Display the specified resource.
     */
    public function show(DocumentCategory $DocumentCategory)
    {
        $this->authorize('view', $DocumentCategory);
        return $this->sendResponse(__("Fetched Successfully"), $DocumentCategory);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentCategoryRequest $request, DocumentCategory $DocumentCategory)
    {
        $DocumentCategory = DB::transaction(function () use ($request, $DocumentCategory) {
            return $DocumentCategory->update($request->all());
        });

        return $this->sendResponse(__("Updated Successfully"), $DocumentCategory);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DocumentCategory $DocumentCategory)
    {
        $this->authorize('delete', $DocumentCategory);
        $DocumentCategory->delete();
        return $this->sendResponse(__("Deleted Successfully"));
    }
}
