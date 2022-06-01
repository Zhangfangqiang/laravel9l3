<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class Policy
{
    use HandlesAuthorization;

    public function __construct()
    {

    }

    /**
     * 在所有验证之前 这里可以做权限系统
     * @param $user
     * @param $ability
     * @return bool
     */
    public function before($user, $ability)
	{
        if ($user->can('manage_contents')) {
            return true;
        }
	}
}
