<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Models\Documents\Letter;
use App\Http\Requests\Documents\StoreLetterRequest;
use App\Http\Requests\Documents\UpdateLetterRequest;
use App\Http\Requests\IndexRequest;

class LetterController extends Controller
{
    public function index(IndexRequest $request)
    {
        $letters = Letter::with(['institution', 'classification'])->paginate(20);
        return $this->sendResponseWithPaginatedData($letters);
    }

    public function store(StoreLetterRequest $request)
    {
        $letter = Letter::create($request->validated());

        return $this->sendResponse(__('Created Successfully'), $letter);
    }

    public function show(Letter $letter)
    {
        return $this->sendResponse(__('Fetched Successfully'), $letter->load(['institution', 'classification']));
    }

    public function update(UpdateLetterRequest $request, Letter $letter)
    {
        $letter->update($request->validated());

        return $this->sendResponse(__('Updated Successfully'), $letter);
    }

    public function destroy(Letter $letter)
    {
        $letter->delete();

        return $this->sendResponse(__('Deleted Successfully'));
    }
}