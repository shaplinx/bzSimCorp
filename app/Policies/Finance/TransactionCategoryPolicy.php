<?php

namespace App\Policies\Finance;

use App\Models\Finance\TransactionCategory;
use App\Models\User;


class TransactionCategoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission("transaction-category:read");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TransactionCategory $transaction_category): bool
    {
        if ($user->hasRole("admin")) return true;
        return $user->hasPermission("transaction-category:read") && $transaction_category->bank->users()->where('id', $user->id)->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission("transaction-category:create");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TransactionCategory $transaction_category): bool
    {
        if ($user->hasRole("admin")) return true;
        return $user->hasPermission("transaction-category:update") && $transaction_category->bank->users()->where('id', $user->id)->exists();

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TransactionCategory $transaction_category): bool
    {
        if ($user->hasRole("admin")) return true;
        return $user->hasPermission("transaction-category:delete") && $transaction_category->bank->users()->where('id', $user->id)->exists();
    }

}
