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
        if ($user->hasRole('admin')) return true;
        return $user->hasPermissionTo('site_faqs.view_any');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SiteFaq $siteFaq): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->hasPermissionTo('site_faqs.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->hasPermissionTo('site_faqs.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SiteFaq $siteFaq): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->hasPermissionTo('site_faqs.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SiteFaq $siteFaq): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->hasPermissionTo('site_faqs.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SiteFaq $siteFaq): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->hasPermissionTo('site_faqs.delete');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SiteFaq $siteFaq): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->hasPermissionTo('site_faqs.delete');
    }
}
