<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VisaProduct;
use Illuminate\Auth\Access\Response;

class VisaProductPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->hasPermissionTo('visa_products.view_any');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, VisaProduct $visaProduct): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->hasPermissionTo('visa_products.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->hasPermissionTo('visa_products.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, VisaProduct $visaProduct): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->hasPermissionTo('visa_products.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, VisaProduct $visaProduct): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->hasPermissionTo('visa_products.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, VisaProduct $visaProduct): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->hasPermissionTo('visa_products.delete');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, VisaProduct $visaProduct): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->hasPermissionTo('visa_products.delete');
    }
}
