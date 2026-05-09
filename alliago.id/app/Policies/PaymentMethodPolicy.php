<?php

namespace App\Policies;

use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PaymentMethodPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->hasPermissionTo('payment_methods.view_any');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PaymentMethod $paymentMethod): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->hasPermissionTo('payment_methods.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->hasPermissionTo('payment_methods.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PaymentMethod $paymentMethod): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->hasPermissionTo('payment_methods.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PaymentMethod $paymentMethod): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->hasPermissionTo('payment_methods.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PaymentMethod $paymentMethod): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->hasPermissionTo('payment_methods.delete');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PaymentMethod $paymentMethod): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->hasPermissionTo('payment_methods.delete');
    }
}
