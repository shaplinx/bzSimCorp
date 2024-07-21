<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\API\V1\IndexRequest;
use Illuminate\Validation\Rules\Password;
use Hash;
use Illuminate\Validation\Rule;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {

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
            ->paginate($request->per_page ?? 10)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => ["required", "string"],
            "email" => ["required", "email", "unique:users,email"],
            "password" => ["required", $this->passwordCriteria(), "confirmed"],
            "password_confirmation" => ["required"],
        ]);

        $request->merge(["password" => Hash::make($request->password)]);
        $user = User::create($request->all());
        return $this->sendResponse(__("Created Successfully"),$user);

    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return $this->sendResponse(__("Fetched Successfully"),$user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            "name" => ["required", "string"],
            "email" => ["required", "email", Rule::unique('users')->ignore($user->id)],
            "old_password" => ["required_with:password", "current_password"],
            "password" => [ $this->passwordCriteria(), "confirmed"],
            "password_confirmation" => ["required_with:password"],
        ]);
        $request->merge(["password" => Hash::make($request->password)]);
        $user->update($request->all());
        return $this->sendResponse(__("Updated Successfully"),$user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return $this->sendResponse(__("Deleted Successfully"));
    }

    /**
     * get Password criteria.
     */
    public function passwordCriteria() : Password
    {
        return Password::min(8)->letters();
    }
}
