<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Mail;

class PasswordController extends Controller
{

    public function forgot(Request $request){
 /*
        0 => Email
        1 => Username
        2 => T.C.
        3 => Phone
        */

        $type           = 0;
        $query          = '';
        $parseone       = explode('@', $request->email);
        $parsetwo       = explode('+', $request->email);
        $credentials    = '';

        if (count($parsetwo) > 1) {
            $type   = 3;
            $query  = 'phone';
        }

        if (count($parseone) > 1) {
            $type   = 0;
            $query  = 'email';
        }else{
            if (filter_var($request->email, FILTER_VALIDATE_INT) == true) {
                $type   = 2;
                $query  = 'tc';
            }else {
                $type   = 1;
                $query  = 'username';
            }
        }

        $user_mail = User::where($query, $request->email)->first();

        if (!$user_mail) {
            return Response::withoutData(false, 'user not found');
        }else{
            $token      = Str::random(64);
            $reseting   = PasswordReset::create([
                'email' => $user_mail->email,
                'token' => $token
            ]);

            $send_mail = Mail::send('mail.forgot', ['token' => $token], function($message) use($user_mail){
                $message->to($user_mail->email);
                $message->subject('Şifre Sıfırlama Talebi');
            });

            if (!$send_mail) {
                return Response::withoutData(false, 'unkown error occured');
            } else {
                return Response::withoutData(true, 'your password reset email has been send');
            }
        }

    }
}
