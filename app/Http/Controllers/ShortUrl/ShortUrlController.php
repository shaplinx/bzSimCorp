<?php

namespace App\Http\Controllers\ShortUrl;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexRequest;
use Illuminate\Http\Request;

use AshAllenDesign\ShortURL\Facades\ShortURL as ShortUrlBuilder;
use App\Models\ShortURL\ShortURL;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ShortUrlController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        Gate::authorize('viewAny', ShortURL::class);

        $user = $request->user();
        $base = $user->isAdmin() ? ShortURL::query() : $user->links(); 

        return $this->sendResponseWithPaginatedData(
            $base
                ->when($request->search, function (Builder $query, string $search) {
                    $query->where(function (Builder $q) use ($search) {
                        $q->where('url_key', 'like', "%{$search}%")
                            ->orWhere('default_short_url', 'like', "%{$search}%")
                            ->orWhere('destination_url', 'like', "%{$search}%");
                    });
                })
                ->when($request->dateAfter, function (Builder $query, string $dateAfter) {
                    $query->whereDate('created_at', '>=', $dateAfter);
                })
                ->when($request->dateBefore, function (Builder $query, $dateBefore) {
                    $query->whereDate('created_at', '<=', $dateBefore);
                })
                ->when($request->user, function (Builder $query, $user) {
                    $query->where('user_id', $user);
                })
                ->paginate($request->pageSize ?? 10)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('store', ShortURL::class);

        $request->validate([
            "url" => "required|url",
            "shortKey" => "nullable|string|min:". config('short-url.key_length')  ."|unique:short_urls,url_key",
            "enableTracking" => "boolean",
            "activateAt" => "date",
            "deactivateAt" => "date",
            "singleUse" => "boolean",
            "forwardQueryParams" => "boolean"
        ]);

        $shortURL = DB::transaction(function () use ($request) {
            return ShortUrlBuilder::destinationUrl($request->url)
                ->beforeCreate(function (ShortURL $model) use ($request): void {
                    $model->user_id = $request->user()->id;
                })
                ->when($request->shortKey, fn($q) => $q->urlKey($request->shortKey))
                ->when($request->forwardQueryParams, fn($q) => $q->forwardQueryParams())
                ->when($request->singleUse, fn($q) => $q->singleUse())
                ->when($request->activateAt, fn($q, $v) => $q->activateAt(Carbon::parse($v)))
                ->when($request->deactivateAt, fn($q, $v) => $q->deactivateAt(Carbon::parse($v)))
                ->when($request->enableTracking, fn($q) => $q->trackVisits())
                ->make();
        });

        return $this->sendResponse(__('Created Successfully'), $shortURL);
    }

    /**
     * Display the specified resource.
     */
    public function show(ShortURL $shortUrl)
    {
        Gate::authorize('view', $shortUrl);

        return $this->sendResponse(__('Fetched Successfully'), $shortUrl->load(['visits']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShortURL $shortUrl)
    {
        Gate::authorize('update', $shortUrl);

        $request->validate([
            "url" => "required|url",
            "shortKey" => [
                'required',
                'string',
                "min:". config('short-url.key_length'),
                Rule::unique('short_urls', 'url_key')->ignore($shortUrl->id),
            ],
            "enableTracking" => "boolean",
            "activateAt" => "date",
            "deactivateAt" => "date",
            "singleUse" => "boolean",
            "forwardQueryParams" => "boolean"
        ]);

        $shortURL = DB::transaction(function () use ($request, $shortUrl) {
            if ($request->has('forwardQueryParams')) {
                $shortUrl->forward_query_params = $request->boolean('forwardQueryParams');
            }
            if ($request->has('singleUse')) {
                $shortUrl->single_use = $request->boolean('singleUse');
            }
            if ($request->has('activateAt')) {
                $shortUrl->activated_at = Carbon::parse($request->activateAt);
            }
            if ($request->has('deactivateAt')) {
                $shortUrl->deactivated_at = Carbon::parse($request->deactivateAt);
            }
            if ($request->has('enableTracking')) {
                $shortUrl->track_visits = $request->boolean('enableTracking');
            }
            $shortUrl->destination_url = $request->url;
            $shortUrl->url_key = $request->shortKey;


            $shortUrl->save();
            return $shortUrl;
        });

        return $this->sendResponse(__('Updated Successfully'), $shortURL->load("visits"));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShortURL $shortUrl)
    {
        Gate::authorize('delete', $shortUrl);
        $shortUrl->delete();
        return $this->sendResponse(__("Deleted Successfully"));
    }
}
