<?php

namespace App\Policies;

use App\Models\SiteFaq;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SiteFaqPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_sitefaq');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SiteFaq $siteFaq): bool
    {
        return $user->hasPermissionTo('view_sitefaq');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_sitefaq');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SiteFaq $siteFaq): bool
    {
        return $user->hasPermissionTo('update_sitefaq');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SiteFaq $siteFaq): bool
    {
        return $user->hasPermissionTo('delete_sitefaq');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SiteFaq $siteFaq): bool
    {
        return $user->hasPermissionTo('delete_sitefaq');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SiteFaq $siteFaq): bool
    {
        return $user->hasPermissionTo('delete_sitefaq');
    }
}
