<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\IndexRequest;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {

        Gate::authorize('viewAny', User::class);
        $request->validate([
            "orderBy.column" => ["required_with:orderBy", Rule::in(Schema::getColumnListing('users'))],
        ]);
        return $this->sendResponseWithPaginatedData(
            User::when($request->search, function (Builder $query, string $search) {
                $query->where(function (Builder $q) use($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->orderBy, function (Builder $query, array $orderBy) {
                $query->orderBy($orderBy['column'],$orderBy['direction']);
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


    /**
     * export the specified resource.
     */
    public function export()
    {
        return (new UsersExport)->download('users.xlsx');
    }

}
