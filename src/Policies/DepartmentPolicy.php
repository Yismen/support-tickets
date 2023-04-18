<?php

namespace Dainsys\Support\Policies;

use Illuminate\Foundation\Auth\User;
use Dainsys\Support\Models\Department;
use Illuminate\Auth\Access\HandlesAuthorization;

class DepartmentPolicy
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
        return $user->can('view departments') || $user->hasAnyRole(['support management']);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \Illuminate\Foundation\Auth\User      $user
     * @param  \Dainsys\Support\Models\Department    $department
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Department $department): bool
    {
        return $user->can('view departments') || $user->hasAnyRole(['support management']);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \Illuminate\Foundation\Auth\User      $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user): bool
    {
        return $user->can('create departments') || $user->hasAnyRole(['support management']);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \Illuminate\Foundation\Auth\User      $user
     * @param  \Dainsys\Support\Models\Department    $department
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Department $department): bool
    {
        return $user->can('update departments') || $user->hasAnyRole(['support management']);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \Illuminate\Foundation\Auth\User      $user
     * @param  \Dainsys\Support\Models\Department    $department
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Department $department): bool
    {
        return $user->can('delete departments') || $user->hasAnyRole(['support management']);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \Illuminate\Foundation\Auth\User      $user
     * @param  \Dainsys\Support\Models\Department    $department
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Department $department): bool
    {
        return $user->can('restore departments') || $user->hasAnyRole(['support management']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \Illuminate\Foundation\Auth\User      $user
     * @param  \Dainsys\Support\Models\Department    $department
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Department $department): bool
    {
        return $user->can('delete departments') || $user->hasAnyRole(['support management']);
    }
}
