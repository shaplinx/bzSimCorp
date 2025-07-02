<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Models\Documents\Classification;
use App\Http\Requests\Documents\StoreClassificationRequest;
use App\Http\Requests\Documents\UpdateClassificationRequest;
use App\Http\Requests\IndexRequest;


class ClassificationController extends Controller
{
    public function index(IndexRequest $request)
    {
        return $this->sendResponse(__('Fetched Successfully'), Classification::all());
    }

    public function store(StoreClassificationRequest $request)
    {
        $classification = Classification::create($request->validated());

        return $this->sendResponse(__('Created Successfully'), $classification);
    }

    public function show(Classification $classification)
    {
        return $this->sendResponse(__('Fetched Successfully'), $classification);
    }

    public function update(UpdateClassificationRequest $request, Classification $classification)
    {
        $classification->update($request->validated());

        return $this->sendResponse(__('Updated Successfully'), $classification);
    }

    public function destroy(Classification $classification)
    {
        $classification->delete();

        return $this->sendResponse(__('Deleted Successfully'));
    }
}