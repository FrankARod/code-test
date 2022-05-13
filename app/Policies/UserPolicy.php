<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function manageProducts(User $user) {
        return $user->subscriptions()->active()->exists() ?
                Response::allow() :
                Response::deny('You must have an active subscription to perform this action.');
    }
}
