<?php

namespace Dainsys\Support\Policies;

use Illuminate\Foundation\Auth\User;
use Dainsys\Support\Models\SuperAdmin;
use Illuminate\Auth\Access\HandlesAuthorization;

class SuperAdminPolicy
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
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \Illuminate\Foundation\Auth\User      $user
     * @param  \Dainsys\Support\Models\SuperAdmin    $department
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, SuperAdmin $department): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \Illuminate\Foundation\Auth\User      $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \Illuminate\Foundation\Auth\User      $user
     * @param  \Dainsys\Support\Models\SuperAdmin    $department
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, SuperAdmin $department): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \Illuminate\Foundation\Auth\User      $user
     * @param  \Dainsys\Support\Models\SuperAdmin    $department
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, SuperAdmin $department): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \Illuminate\Foundation\Auth\User      $user
     * @param  \Dainsys\Support\Models\SuperAdmin    $department
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, SuperAdmin $department): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \Illuminate\Foundation\Auth\User      $user
     * @param  \Dainsys\Support\Models\SuperAdmin    $department
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, SuperAdmin $department): bool
    {
        return $user->isSuperAdmin();
    }
}
