<?php

namespace App\Policies\Finance;

use App\Models\Finance\Loan;
use App\Models\User;


class LoanPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission("loan:read");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Loan $loan): bool
    {
        if ($user->hasRole("admin")) return true;
        return $user->hasPermission("loan:read") && $loan->bank->users()->where('id', $user->id)->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission("loan:create");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Loan $loan): bool
    {
        if ($user->hasRole("admin")) return true;
        return $user->hasPermission("loan:update") && $loan->bank->users()->where('id', $user->id)->exists();

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Loan $loan): bool
    {
        if ($user->hasRole("admin")) return true;
        return $user->hasPermission("loan:delete") && $loan->bank->users()->where('id', $user->id)->exists();
    }

}
