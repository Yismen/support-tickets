<?php

namespace Dainsys\Support\Policies;

use Dainsys\Support\Models\Ticket;
use Illuminate\Foundation\Auth\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketPolicy
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
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \Illuminate\Foundation\Auth\User      $user
     * @param  \Dainsys\Support\Models\Ticket        $ticket
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Ticket $ticket): bool
    {
        return $user->id === $ticket->created_by
            || $user->isDepartmentAdmin($ticket->department)
            || $user->isDepartmentAgent($ticket->department)
            ;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \Illuminate\Foundation\Auth\User      $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user): bool
    {
        return true ;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \Illuminate\Foundation\Auth\User      $user
     * @param  \Dainsys\Support\Models\Ticket        $ticket
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Ticket $ticket): bool
    {
        return $user->id === $ticket->created_by;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \Illuminate\Foundation\Auth\User      $user
     * @param  \Dainsys\Support\Models\Ticket        $ticket
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->id === $ticket->created_by;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \Illuminate\Foundation\Auth\User      $user
     * @param  \Dainsys\Support\Models\Ticket        $ticket
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Ticket $ticket): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \Illuminate\Foundation\Auth\User      $user
     * @param  \Dainsys\Support\Models\Ticket        $ticket
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Ticket $ticket): bool
    {
        return $user->id === $ticket->created_by;
    }
}
