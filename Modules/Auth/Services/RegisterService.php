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
    )
    {

    }

    /**
     * The module for restaurants
     *
     * @var string
     */
    private static $restaurantModel = Restaurant::class;

    public function register(TDO $userData)
    {
        DB::beginTransaction();

        try {
            $data = collect(
                $userData->all(asSnake: true)
            );

            // get userdata only.
            $createData = $data->except(['as_restaurant', 'restaurant_name']);
            // hashin the password.
            $createData['password'] = Hash::make($createData['password']);
            // create the user.
            $user = self::$model::create($createData->toArray());
            // chose role 'restaurant' if asRestaurant equals true.
            $role = $userData->asRestaurant ? 'restaurant' : 'customer';
            // assign role to user.
            $user->assignRole($role);

            // create restaurant record if register as restaurant.
            if ( $userData->asRestaurant ) {
                // get spcific keys for restaurant creation proces.
                $restaurantData = $data->only(['restaurant_name']);

                // assign user id as owner for restaurant.
                $restaurantData['owner_id'] = $user->id;

                // create restaurant record in database.
                self::$restaurantModel::create($restaurantData->toArray());
            }

            // send verification code here
            $this->verifiedEmailService->sendVerificationCode($user);

            // transaction is done.
            DB::commit();

            return Result::done($user);
        } catch (\Throwable $e) {
            DB::rollBack();
            return Result::error($e->getMessage());
        }
    }
}
