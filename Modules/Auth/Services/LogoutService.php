<?php

namespace Modules\Auth\Services;

use Modules\Auth\Entities\User;

class LogoutService
{
    private static $models = User::class;
    
    public function logout(object $user)
    {      

    try {
        $deleted =  $user->currentAccessToken()
        ->delete();

    return $deleted && true;

    }catch (\Throwable $e) {
        return $e;
      }


    }

}
