<?php


namespace Modules\Auth\Services;

use Graphicode\Standard\TDO\TDO;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordService
{

    public function changePassword(TDO $tdo)
    {
        try{
            $user = Auth::user();
    
            if (!Hash::check($tdo->password, $user->password)) {
                return null;
            }
            // updationg the password.
            $user->password = Hash::make($tdo->newPassword);
            $user->save();
            return $user = Auth::user();

        }catch(\Throwable $e){
            return $e;
        }

    }
  
}
