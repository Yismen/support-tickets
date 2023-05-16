<?php

namespace Dainsys\Support\Policies;

use Dainsys\Support\Models\Reply;
use Illuminate\Foundation\Auth\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReplyPolicy
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
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \Illuminate\Foundation\Auth\User      $user
     * @param  \Dainsys\Support\Models\Reply         $reply
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Reply $reply): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \Illuminate\Foundation\Auth\User      $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Reply $reply, $ticket): bool
    {
        return $ticket->created_by === $user->id || $ticket->assigned_to === $user->id;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \Illuminate\Foundation\Auth\User      $user
     * @param  \Dainsys\Support\Models\Reply         $reply
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Reply $reply): bool
    {
        return $user->id === $reply->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \Illuminate\Foundation\Auth\User      $user
     * @param  \Dainsys\Support\Models\Reply         $reply
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Reply $reply): bool
    {
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \Illuminate\Foundation\Auth\User      $user
     * @param  \Dainsys\Support\Models\Reply         $reply
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Reply $reply): bool
    {
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \Illuminate\Foundation\Auth\User      $user
     * @param  \Dainsys\Support\Models\Reply         $reply
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Reply $reply): bool
    {
    }
}
