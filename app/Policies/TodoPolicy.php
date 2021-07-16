<?php

namespace App\Policies;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TodoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the todo can view any models.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('list todos');
    }

    /**
     * Determine whether the todo can view the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Todo  $model
     * @return mixed
     */
    public function view(User $user, Todo $model)
    {
        return $user->hasPermissionTo('view todos');
    }

    /**
     * Determine whether the todo can create models.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create todos');
    }

    /**
     * Determine whether the todo can update the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Todo  $model
     * @return mixed
     */
    public function update(User $user, Todo $model)
    {
        return $user->hasPermissionTo('update todos');
    }

    /**
     * Determine whether the todo can delete the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Todo  $model
     * @return mixed
     */
    public function delete(User $user, Todo $model)
    {
        return $user->hasPermissionTo('delete todos');
    }

    /**
     * Determine whether the user can delete multiple instances of the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Todo  $model
     * @return mixed
     */
    public function deleteAny(User $user)
    {
        return $user->hasPermissionTo('delete todos');
    }

    /**
     * Determine whether the todo can restore the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Todo  $model
     * @return mixed
     */
    public function restore(User $user, Todo $model)
    {
        return false;
    }

    /**
     * Determine whether the todo can permanently delete the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Todo  $model
     * @return mixed
     */
    public function forceDelete(User $user, Todo $model)
    {
        return false;
    }
}
