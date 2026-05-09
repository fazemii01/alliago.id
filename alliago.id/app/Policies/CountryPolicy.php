<?php

namespace App\Policies;

use App\Models\Country;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CountryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->hasPermissionTo('countries.view_any');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Country $country): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->hasPermissionTo('countries.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->hasPermissionTo('countries.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Country $country): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->hasPermissionTo('countries.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Country $country): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->hasPermissionTo('countries.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Country $country): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->hasPermissionTo('countries.delete');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Country $country): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->hasPermissionTo('countries.delete');
    }
}
