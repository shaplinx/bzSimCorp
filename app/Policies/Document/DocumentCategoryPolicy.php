<?php

namespace App\Policies\Finance;

use App\Models\Finance\DocumentCategory;
use App\Models\User;


class DocumentCategoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission("document-category:read");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DocumentCategory $DocumentCategory): bool
    {
        if ($user->hasRole("admin")) return true;
        return $user->hasPermission("document-category:read");
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission("document-category:create");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DocumentCategory $DocumentCategory): bool
    {
        if ($user->hasRole("admin")) return true;
        return $user->hasPermission("document-category:update");

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DocumentCategory $DocumentCategory): bool
    {
        if ($user->hasRole("admin")) return true;
        return $user->hasPermission("document-category:delete");
    }

}
