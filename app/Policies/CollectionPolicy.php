<?php

namespace App\Policies;

use App\Models\Collection;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CollectionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return (bool) $user;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Collection $collection
     * @return bool
     */
    public function view(User $user, Collection $collection): bool
    {
        return $collection->user->id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user): bool
    {
        return (bool) $user;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Collection $collection
     * @return mixed
     */
    public function update(User $user, Collection $collection): bool
    {
        return $collection->user->id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Collection $collection
     * @return mixed
     */
    public function delete(User $user, Collection $collection): bool
    {
        return $collection->user->id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Collection $collection
     * @return mixed
     */
    public function restore(User $user, Collection $collection): bool
    {
        return $collection->user->id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Collection $collection
     * @return mixed
     */
    public function forceDelete(User $user, Collection $collection): bool
    {
        return $collection->user->id === $user->id;
    }
}
