<?php

namespace Tests\Traits;

use App\Models\User;

trait ActingJWTUser
{
    public function JWTActingAs(User $user)
    {

        $this->withHeaders(['Authorization' => 'Bearer '.$user->createToken('api')->plainTextToken]);

        return $this;
    }
}
