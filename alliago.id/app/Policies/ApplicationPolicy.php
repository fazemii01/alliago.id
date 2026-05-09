<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ApplicationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->hasPermissionTo('applications.view_any');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Application $application): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->hasPermissionTo('applications.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->hasPermissionTo('applications.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Application $application): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->hasPermissionTo('applications.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Application $application): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->hasPermissionTo('applications.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Application $application): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->hasPermissionTo('applications.delete');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Application $application): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->hasPermissionTo('applications.delete');
    }
}
