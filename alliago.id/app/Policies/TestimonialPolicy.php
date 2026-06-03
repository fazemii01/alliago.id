<?php

namespace App\Policies;

use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TestimonialPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->can('testimonials.view_any');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Testimonial $testimonial): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->can('testimonials.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->can('testimonials.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Testimonial $testimonial): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->can('testimonials.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Testimonial $testimonial): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->can('testimonials.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Testimonial $testimonial): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->can('testimonials.delete');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Testimonial $testimonial): bool
    {
        if ($user->hasRole('admin')) return true;
        return $user->can('testimonials.delete');
    }
}
