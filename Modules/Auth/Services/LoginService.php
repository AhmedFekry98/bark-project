<?php

namespace Modules\Auth\Services;

use App\ErrorHandlling\Result;
use Graphicode\Standard\TDO\TDO;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Entities\User;
use Modules\Auth\Enums\ErrorCode;
use Modules\Sanctum\Services\Users\UsersServiceFactory;

class LoginService
{
    private static $models = User::class;

    private $getBy  = 'email';


    public function login(TDO $loginData) // return int value only if has error code
    {

        try {
            $user = self::$models::where($this->getBy, $loginData->{$this->getBy})->first();

            if ( !$user || !Hash::check($loginData->password, $user->password)) {
                return Result::error('Invalid Credentials');
            }

            return Result::done($user);
        } catch (\Throwable $e) {
            return Result::error($e->getMessage());
        }
    }
}
