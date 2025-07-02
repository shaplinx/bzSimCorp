<?php

namespace App\Http\Controllers\Documents;

use App\Models\Documents\Institution;
use App\Http\Requests\Documents\StoreInstitutionRequest;
use App\Http\Requests\Documents\UpdateInstitutionRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\IndexRequest;

class InstitutionController extends Controller
{
    public function index(IndexRequest $request)
    {
        return $this->sendResponse(__('Fetched Successfully'), Institution::all());
    }

    public function store(StoreInstitutionRequest $request)
    {
        $institution = Institution::create($request->validated());

        return $this->sendResponse(__('Created Successfully'), $institution);
    }

    public function show(Institution $institution)
    {
        return $this->sendResponse(__('Fetched Successfully'), $institution);
    }

    public function update(UpdateInstitutionRequest $request, Institution $institution)
    {
        $institution->update($request->validated());

        return $this->sendResponse(__('Updated Successfully'), $institution);
    }

    public function destroy(Institution $institution)
    {
        $institution->delete();

        return $this->sendResponse(__('Deleted Successfully'));
    }
}