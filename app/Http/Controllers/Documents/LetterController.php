<?php

namespace App\Http\Controllers\Documents;

use App\Http\Controllers\Controller;
use App\Models\Documents\Letter;
use App\Http\Requests\Documents\StoreLetterRequest;
use App\Http\Requests\Documents\UpdateLetterRequest;
use App\Http\Requests\IndexRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LetterController extends Controller
{
    public function index(IndexRequest $request)
    {
        $letters = Letter::with(['institution', 'classification'])->paginate(20);
        return $this->sendResponseWithPaginatedData($letters);
    }

    public function store(StoreLetterRequest $request)
    {

        $letter = DB::transaction(function () use ($request) {
            $letter = Letter::make($request->validated());
            if ($request->input('status') === "void") {
                $letter->void = Carbon::now();
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
        $data = $request->validated();

        if ($request->input('status') === "void") {
            $data['voided_at'] = now();
        }

        if ($request->input('status') === "issued") {
            $data['issued_at'] = now();
        }
        if ($request->input('status') === "draft") {
            $data['issued_at'] = null;
            $data['voided_at'] = null;
        }

        $letter->fill($data);
        $letter->save();

        return $letter;
    }


    public function destroy(Letter $letter)
    {
        $letter->delete();

        return $this->sendResponse(__('Deleted Successfully'));
    }
}
