<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\API\V1\IndexRequest;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\API\V1\User\StoreUserRequest;
use App\Http\Requests\API\V1\User\UpdateUserRequest;
use Illuminate\Support\Facades\DB;


class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {

        Gate::authorize('viewAny', User::class);
        return $this->sendResponseWithPaginatedData(
            User::when($request->search, function (Builder $query, string $search) {
                $query->where(function (Builder $q) use($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->orderBy, function (Builder $query, string $orderBy) {
                $orderBy = explode('|',$orderBy);
                $query->orderBy($orderBy[0],$orderBy[1]);
            })
            ->paginate($request->pageSize ?? 10)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
       $user = DB::transaction(function () use($request) {
            $user = User::create($request->all());
            $user->setRoles($request->roles);
            return $user->refresh();
        });

        return $this->sendResponse(__("Created Successfully"),$user);

    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        Gate::authorize('view', $user);
        return $this->sendResponse(__("Fetched Successfully"),$user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user = DB::transaction(function () use($request, $user) {
            $user->update($request->all());
            $user->setRoles($request->roles);
            return $user->refresh();
        });

        return $this->sendResponse(__("Updated Successfully"),$user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);
        $user->delete();
        return $this->sendResponse(__("Deleted Successfully"));
    }

}
