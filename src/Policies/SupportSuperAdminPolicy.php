<?php

namespace Dainsys\Support\Policies;

use Illuminate\Foundation\Auth\User;
use Dainsys\Support\Models\SupportSuperAdmin;
use Illuminate\Auth\Access\HandlesAuthorization;

class SupportSuperAdminPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \Illuminate\Foundation\Auth\User      $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user): bool
    {
        return $user->isSupportSuperAdmin();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \Illuminate\Foundation\Auth\User          $user
     * @param  \Dainsys\Support\Models\SupportSuperAdmin $department
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, SupportSuperAdmin $department): bool
    {
        return $user->isSupportSuperAdmin();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \Illuminate\Foundation\Auth\User      $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user): bool
    {
        return $user->isSupportSuperAdmin();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \Illuminate\Foundation\Auth\User          $user
     * @param  \Dainsys\Support\Models\SupportSuperAdmin $department
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, SupportSuperAdmin $department): bool
    {
        return $user->isSupportSuperAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \Illuminate\Foundation\Auth\User          $user
     * @param  \Dainsys\Support\Models\SupportSuperAdmin $department
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, SupportSuperAdmin $department): bool
    {
        return $user->isSupportSuperAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \Illuminate\Foundation\Auth\User          $user
     * @param  \Dainsys\Support\Models\SupportSuperAdmin $department
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, SupportSuperAdmin $department): bool
    {
        return $user->isSupportSuperAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \Illuminate\Foundation\Auth\User          $user
     * @param  \Dainsys\Support\Models\SupportSuperAdmin $department
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, SupportSuperAdmin $department): bool
    {
        return $user->isSupportSuperAdmin();
    }
}
