<?php

namespace App\Policies\ShortURL;

use App\Models\ShortURL\ShortURL;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class ShortURLPolicy
{
    /**
     * Determine whether the url can view any models.
     */
    public function viewAny(User $user): Response
    {
        if($user->isAdmin()) return Response::allow();
        return $user->hasPermission("url:read")
        ? Response::allow()
        : Response::deny('You have no access to see the urls.');
    }
    /**
     * Determine whether the url can view the model.
     */
    public function view(User $user, ShortURL $model): Response
    {       
        if($user->isAdmin()) return Response::allow();
        if($user->id !== $model->user_id) return Response::deny('You have no access to see this url.');
        return $model->hasPermission("url:read")
        ? Response::allow()
        : Response::deny('You have no access to see this url.');
    }

    /**
     * Determine whether the url can create models.
     */
    public function create(User $user): Response
    {
        return $user->hasPermission("url:create")
        ? Response::allow()
        : Response::deny('You have no access to create new url.');
    }

    /**
     * Determine whether the url can update the model.
     */
    public function update(User $user, ShortURL $model): Response
    {   
        if($user->isAdmin()) return Response::allow();
        if($user->id !== $model->user_id) return Response::deny('You have no access to update this url.');
        return $model->hasPermission("url:update")
        ? Response::allow()
        : Response::deny('You have no access to update this url.');

    }

    /**
     * Determine whether the url can delete the model.
     */
    public function delete(User $user, ShortURL $model): Response
    {
        if($user->isAdmin()) return Response::allow();
        if($user->id !== $model->user_id) return Response::deny('You have no access to delete this url.');
        return $user->hasPermission("url:delete")
        ? Response::allow()
        : Response::deny('You have no access to delete this url.');
    }

}
