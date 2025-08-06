<?php

namespace App\Http\Controllers\Documents;

use App\Exports\Documents\LetterExport;
use App\Http\Controllers\Controller;
use App\Models\Documents\Letter;
use App\Http\Requests\Documents\StoreLetterRequest;
use App\Http\Requests\Documents\UpdateLetterRequest;
use App\Http\Requests\IndexRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use Illuminate\Database\Eloquent\Builder;
use App\Rules\ColumnExists;
use Illuminate\Support\Facades\Auth;

class LetterController extends Controller
{
    public function index(IndexRequest $request)
    {
        $request->validate([
            "orderBy.column" => ["required_with:orderBy", new ColumnExists((new Letter)->getTable())],
            "letterInstitutions" => ["nullable", "array"],
            "letterInstitutions" => ["nullable", "array"],
            "letterInstitutions.*" => ["required_with:letterInstitutions", 'numeric'],
            "letterStatus" => ["nullable", "in:draft,issued,void"]
        ]);

        return $this->sendResponseWithPaginatedData(
            Letter::with(['institution', 'classification'])
                ->when($request->search, function (Builder $query, string $search) {
                    $query->where(function (Builder $q) use ($search) {
                        $q->where('subject', 'like', "%{$search}%")
                            ->orWhere('recipient', 'like', "%{$search}%")
                            ->orWhere('letter_number', 'like', "%{$search}%")
                            ->orWhere('sn', 'like', "%{$search}%");

                    });
                })
                ->when($request->letterPublic, function (Builder $query, bool $public) {
                    $query->where('public', $public);
                })
                ->when($request->dateAfter, function (Builder $query, string $dateAfter) {
                    $query->whereDate('letter_date', '>=', $dateAfter);
                })
                ->when($request->dateBefore, function (Builder $query, $dateBefore) {
                    $query->whereDate('letter_date', '<=', $dateBefore);
                })
                ->when($request->letterInstitutions, function (Builder $query, $letterInstitutions) {
                    $query->whereIn('institution_id', $letterInstitutions);
                })
                ->when($request->letterClassifications, function (Builder $query, $letterClassifications) {
                    $query->whereIn('classification_id', $letterClassifications);
                })
                ->when($request->letterStatus, function (Builder $query, string $status) {
                    switch ($status) {
                        case 'draft':
                            $query->whereNull('voided_at');
                            $query->whereNull('issued_at');
                            break;
                        case 'issued':
                            $query->whereNull('voided_at');
                            $query->whereNotNull('issued_at');
                            break;
                        case 'void':
                            $query->whereNotNull('voided_at');
                            break;
                        default:
                            break;
                    }
                })
                ->when($request->orderBy, function (Builder $query, array $orderBy) {
                    $query->orderBy($orderBy['column'] ?? "created_at", $orderBy['direction'] ?? "DESC");
                })
                ->paginate($request->pageSize ?? 10)
        );
    }

    public function store(StoreLetterRequest $request)
    {

        $letter = DB::transaction(function () use ($request) {
            $letter = Letter::make($request->validated());
            if ($request->input('status') === "void") {
                $letter->void = Carbon::now();
            }

            if ($request->input('status') === "issued") {
                $letter->issued_at = Carbon::now();
            }

            if ($request->hasFile('file')) {
                $letter->file_path = $request->file('file')->store("letters", 'local');
            }

            $letter->save();
            return $letter;
        });

        return $this->sendResponse(__('Created Successfully'), $letter);
    }

    public function show(Letter $letter)
    {
        return $this->sendResponse(__('Fetched Successfully'), $letter->load(['institution', 'classification']));
    }

    public function update(UpdateLetterRequest $request, Letter $letter)
    {

        $letter = DB::transaction(function () use ($request, $letter) {
            $data = $request->validated();

            if (!$letter->voided_at && $request->input('status') === "void") {
                $data['voided_at'] = now();
            }

            if (!$letter->issued_at && $request->input('status') === "issued") {
                $data['issued_at'] = now();
            }
            if ($request->input('status') === "draft") {
                $data['issued_at'] = null;
                $data['voided_at'] = null;
            }
            if ($request->hasFile('file')) {
                $oldFile = $letter->file_path;

                $data['file_path'] = $request->file('file')->store("letters", 'local');
            }

            $letter->fill($data);


            $letter->save();
            if (isset($oldFile)) {
                Storage::disk('local')->delete($oldFile);
            }

            return $letter;
        });

        return $this->sendResponse(__('Updated Successfully'), $letter);
    }


    public function destroy(Letter $letter)
    {
        $letter->delete();
        if ($path = $letter->getRawOriginal('file_path')) {
            Storage::disk('local')->delete($path);
        }

        return $this->sendResponse(__('Deleted Successfully'));
    }

    public function download(Letter $letter)
    {
        if (!Auth::check() && !$letter->public) {
            abort(401, "Unauthorized");
        }
        if (!$letter->file_path || !Storage::disk('local')->exists($letter->file_path)) {
            abort(404, 'File not found');
        }
        /** @var \Illuminate\Filesystem\FilesystemAdapter $storage */
        $storage = Storage::disk('local');
        return $storage->download($letter->file_path);
    }

     /**
     * export the specified resource.
     */
    public function export()
    {
        return (new LetterExport)->download('letters.xlsx');
    }
}
