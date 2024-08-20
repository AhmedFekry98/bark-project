<?php

namespace Modules\Auth\Services;

use App\ErrorHandlling\Result;
use App\Mail\VerificationCodeMail as MailVerificationCodeMail;
use Modules\Auth\Entities\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Modules\Auth\Entities\OtpPassword;


class VerifiedEmailService
{
    public static $otpModel = OtpPassword::class;

    public function sendVerificationCode(User $user)
    {
        try {
            $code = str_pad(random_int(0000, 9999), 4, '0', STR_PAD_LEFT);
            $otp = self::$otpModel::create([
                'email' => $user->email,
                'otp'   => $code,
                'token' => Str::random(60),
                'expire_at' => now()->addMinute(5),
            ]);

            Mail::to($otp->email)
                ->send(new MailVerificationCodeMail($otp->otp));

            return Result::done($otp);
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }

    public function checkVerificationCode(User $user, string $code)
    {
        try {

            $otp = self::$otpModel::where('email', $user->email)
                ->where('expire_at', '>', now())
                ->whereNotNull('token')
                ->first();

            if ($otp && $otp->otp == $code) {
                return Result::done($otp);
            } else {
                return Result::error("Invalid OTP Code");
            }
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }

    public function verifyUser(User $user, string $code)
    {
        try {

            if ($code == '0000' && config('app.env') != 'production') {
                $user->verified_at = now();
                $user->save();
                return Result::done(true);
            }

            $result = $this->checkVerificationCode($user, $code);
            if ($result->isError()) return $result;
            $otp = $result->data;

            $user->verified_at = now();
            $user->save();

            $otp->token = null;
            $otp->expire_at = now();
            $otp->save();

            return Result::done(true);
        } catch (\Exception $ee) {
            return Result::error($e->getMessage);
        }
    }
}
