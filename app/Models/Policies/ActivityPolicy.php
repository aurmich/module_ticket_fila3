<?php

declare(strict_types=1);

namespace Modules\Ticket\Models\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Ticket\Models\Activity;
use Modules\Xot\Contracts\UserContract;

class ActivityPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(UserContract $user)
    {
        return $user->can('List activities');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(UserContract $user, Activity $activity)
    {
        return $user->can('View activity');
    }

    /**
     * Determine whether the user can create models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(UserContract $user)
    {
        return $user->can('Create activity');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(UserContract $user, Activity $activity)
    {
        return $user->can('Update activity');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(UserContract $user, Activity $activity)
    {
        return $user->can('Delete activity');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(UserContract $user, Activity $activity)
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(UserContract $user, Activity $activity)
    {
        return true;
    }
}
