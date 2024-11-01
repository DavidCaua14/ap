<?php

namespace App\Policies;

use App\Models\Objeto;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ObjetoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Objeto $objeto): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->level >= User::ADMIN_LEVEL;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Objeto $objeto): bool
    {
        return $user->level >= User::ADMIN_LEVEL;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Objeto $objeto): bool
    {
        return $user->level >= User::ADMIN_LEVEL;
    }

}
