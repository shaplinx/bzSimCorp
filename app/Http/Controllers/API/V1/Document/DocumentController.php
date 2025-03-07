<?php

namespace App\Http\Controllers\API\V1\Finance;

use App\Http\Requests\API\V1\Finance\StoreDocumentRequest;
use App\Http\Requests\API\V1\Finance\UpdateDocumentRequest;
use App\Models\Finance\Document;
use App\Http\Controllers\API\V1\ApiController;
use App\Http\Requests\API\V1\IndexRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;


class DocumentController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {

        $this->authorize('viewAny', Document::class);
        $data = Document::when($request->search, function (Builder $query, string $search) {
                $query->scopeSearch(['title', 'number', 'author', 'about', 'status', 'signed_by', 'signed_place',]);
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
    public function store(StoreDocumentRequest $request)
    {
        $Document = DB::transaction(function () use ($request) {
            $document = Document::create($request->all());
            if ($request->filled('revised')) {
                $document->revised()->attach(collect($request->revised->pluck('id')));
            }
    
            if ($request->filled('revising')) {
                $document->revising()->attach(collect($request->revising->pluck('id')));
            }
            return $document;
        });

        return $this->sendResponse(__("Created Successfully"), $Document);
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $Document)
    {
        $this->authorize('view', $Document);
        return $this->sendResponse(__("Fetched Successfully"), $Document);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDocumentRequest $request, Document $Document)
    {
        $Document = DB::transaction(function () use ($request, $Document) {
            $document= $Document->update($request->all());

            if ($request->filled('revised')) {
                $document->revised()->sync(collect($request->revised->pluck('id')));
            }
    
            if ($request->filled('revising')) {
                $document->revising()->sync(collect($request->revising->pluck('id')));
            }
            return $document;
        });

        return $this->sendResponse(__("Updated Successfully"), $Document);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $Document)
    {
        $this->authorize('delete', $Document);
        $Document->delete();
        return $this->sendResponse(__("Deleted Successfully"));
    }
}
