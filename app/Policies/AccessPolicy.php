<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccessPolicy
{

    use HandlesAuthorization;
    
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function admin(User $user) {
        return $user->isAdministrator();
    }

}
