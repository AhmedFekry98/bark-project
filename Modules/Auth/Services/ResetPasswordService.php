<?php

namespace Modules\Auth\Services;

use Graphicode\Standard\TDO\TDO;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Entities\OtpPassword;
use Modules\Auth\Entities\User;

class ResetPasswordService
{

    private static $model = OtpPassword::class;
    private static $userModel = User::class;
    private static $expirdToken  = 'expird';
    private static $getBy = 'email';

    public function resetPassword(TDO $tdo)
    {
        try {
            $token = $tdo->otpToken;
            $otpRecord = self::$model::where('token', $token)
                ->where('expire_at', '>', now())
                ->first();

            if (!$otpRecord) {
                throw new \Exception('Token Expired');
            }

            $user = self::$userModel::where(static::$getBy, $otpRecord->email)->first();

            // update password
            $user->password = Hash::make($tdo->password);
            $user->save();

            return $user;
        } catch (\Throwable $e) {
            return['error' => $e->getMessage()];
        }
    }
}
