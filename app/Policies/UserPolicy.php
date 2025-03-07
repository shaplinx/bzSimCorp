<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $user->hasPermission("user:read")
        ? Response::allow()
        : Response::deny('You have no access to see the users.');
    }
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): Response
    {
        return $user->hasPermission("user:read")
        ? Response::allow()
        : Response::deny('You have no access to see this user.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->hasPermission("user:create")
        ? Response::allow()
        : Response::deny('You have no access to create new user.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): Response
    {
        return $user->hasPermission("user:update") || $user->id === $model->id
        ? Response::allow()
        : Response::deny('You have no access to update this user.');

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): Response
    {
        return $user->hasPermission("user:delete")
        ? Response::allow()
        : Response::deny('You have no access to delete this user.');
    }

}
