<?php


namespace Modules\Auth\Services;

use Graphicode\Standard\TDO\TDO;
use Modules\Auth\Entities\OtpPassword;

class CheckOtpService
{

    private static $model = OtpPassword::class;

    public function checkOtp(TDO $tdo)
    {
        try {
            $email = $tdo->email;
            $otp =   $tdo->otp;

            $otpRecourd = self::$model::where('email', $email)
                ->where('expire_at', '>', now())
                ->whereNotNull('token')
                ->first();

            if (!$otpRecourd) {
                throw new \Exception('Invalid Email');
            }

            if ( config('app.env')  != 'production' && $otp == '0000') {
                return $otpRecourd->token;
            }

            if ($otpRecourd->otp !== $otp) {
                throw new \Exception('Invalid OTP Code');
            }

            return $otpRecourd->token;
        } catch (\Throwable $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
