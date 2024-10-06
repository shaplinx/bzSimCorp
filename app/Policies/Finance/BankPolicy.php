<?php

namespace App\Policies\Finance;

use App\Models\Finance\Bank;
use App\Models\User;
use Illuminate\Auth\Access\Response;


class BankPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission("bank:read");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Bank $bank): bool
    {
        if ($user->hasRole("admin")) return true;
        return $user->hasPermission("bank:read") && $bank->users()->where('id', $user->id)->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission("bank:create");

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Bank $bank): bool
    {
        if ($user->hasRole("admin")) return true;
        return $user->hasPermission("bank:update") && $bank->users()->where('id', $user->id)->exists();

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Bank $bank): bool
    {
        if ($user->hasRole("admin")) return true;
        return $user->hasPermission("bank:delete") && $bank->users()->where('id', $user->id)->exists();


    }

}
