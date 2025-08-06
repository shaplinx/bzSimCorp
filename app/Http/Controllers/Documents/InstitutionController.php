<?php

namespace App\Http\Controllers\Documents;

use App\Exports\Documents\InstitutionExport;
use App\Models\Documents\Institution;
use App\Http\Requests\Documents\StoreInstitutionRequest;
use App\Http\Requests\Documents\UpdateInstitutionRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\IndexRequest;
use Illuminate\Database\Eloquent\Builder;


class InstitutionController extends Controller
{
    public function index(IndexRequest $request)
    {
        
        return $this->sendResponseWithPaginatedData(
            Institution::
                when($request->search, function (Builder $query, string $search) {
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

        /**
     * export the specified resource.
     */
    public function export()
    {
        return (new InstitutionExport)->download('institution.xlsx');
    }
}