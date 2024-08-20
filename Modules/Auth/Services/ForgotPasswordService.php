<?php


namespace Modules\Auth\Services;
use Graphicode\Standard\TDO\TDO;
use Illuminate\Support\Str;
use Modules\Auth\Entities\OtpPassword;

class ForgotPasswordService
{

    private static $model = OtpPassword::class;

    public function forgotPassword(TDO $tdo)
    {
        try{
            $email = $tdo->email;
            $otp = str_pad(random_int(0000, 9999), 4, '0', STR_PAD_LEFT); 
            $attributes = [
                'email' => $email,
                'otp'   => $otp,
                'token' => Str::random(60),
                'expire_at' => now()->addMinute(5),
            ];
            $forgotPassword = self::$model::create($attributes);
            $forgotPassword = substr($email, 0, 2) . str_repeat('*', strpos($email, '@') - 2) . substr($email, strpos($email, '@'));
            return $forgotPassword;
        }catch(\Throwable $e){
            return $e;
        }




    }

  

}
