<?php

namespace App\Policies\Finance;

use App\Models\Finance\Document;
use App\Models\User;
use Illuminate\Auth\Access\Response;


class DocumentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission("document:read");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Document $Document): bool
    {
        if ($user->hasRole("admin")) return true;
        return $user->hasPermission("document:read");
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission("document:create");

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Document $Document): bool
    {
        if ($user->hasRole("admin")) return true;
        return $user->hasPermission("document:update");

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Document $Document): bool
    {
        if ($user->hasRole("admin")) return true;
        return $user->hasPermission("document:delete");


    }

}
