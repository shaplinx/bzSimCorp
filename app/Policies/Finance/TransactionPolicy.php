<?php

namespace App\Policies\Finance;

use App\Models\Finance\Transaction;
use App\Models\User;


class TransactionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission("transaction:read");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Transaction $transaction): bool
    {
        if ($user->hasRole("admin")) return true;
        return $user->hasPermission("transaction:read") && $transaction->bank->users()->where('id', $user->id)->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission("transaction:create");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Transaction $transaction): bool
    {
        if ($user->hasRole("admin")) return true;
        return $user->hasPermission("transaction:update") && $transaction->bank->users()->where('id', $user->id)->exists();

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Transaction $transaction): bool
    {
        if ($user->hasRole("admin")) return true;
        return $user->hasPermission("transaction:delete") && $transaction->bank->users()->where('id', $user->id)->exists();
    }

}
