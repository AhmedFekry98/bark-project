<?php

namespace Modules\Auth\Services;

use App\ErrorHandlling\Result;
use Graphicode\Standard\TDO\TDO;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Entities\User;
use Modules\Restaurants\Entities\Restaurant;

class RegisterService
{
    const INVALID_CREDENTIAL =  'invalid_credential';

    private static $model = User::class;

    public function __construct(
        private VerifiedEmailService $verifiedEmailService
    ) {}

    /**
     * The module for restaurants
     *
     * @var string
     */
    private static $restaurantModel = Restaurant::class;

    public function register(string $role, TDO $userData)
    {
        DB::beginTransaction();

        try {
            $data = collect(
                $userData->all(asSnake: true)
            );

            // get userdata only.
            $createData = $data
                // ->except([])
            ;

            // hashin the password.
            $createData['password'] = Hash::make($createData['password']);

            // create the user.
            $user = self::$model::create($createData->toArray());

            // assign role to user.
            $user->assignRole($role);


            // send verification code here
            // $this->verifiedEmailService->sendVerificationCode($user);

            // transaction is done.
            DB::commit();

            return Result::done($user);
        } catch (\Throwable $e) {
            DB::rollBack();
            return Result::error($e->getMessage());
        }
    }
}
