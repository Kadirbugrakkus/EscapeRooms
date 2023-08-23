<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginValidationRequest;
use App\Http\Resources\LoginResource;
use App\Http\Resources\UserResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(LoginValidationRequest $request)
    {
        $validator = Validator::make($request->all(), (new LoginValidationRequest())->rules());

        if ($validator->fails()) {
            return Response::withoutData(false, $validator->errors());
        }else{
            $credentials = $request->only('email', 'password');

            if (auth()->attempt($credentials)) {
                $user = auth()->user();
                $token = $user->createToken('auth_token')->plainTextToken;

                $data = new LoginResource($user);
                $data->user_token = $token;

                return Response::withData(true, 'user logged in', $data);
            }
            return Response::withoutData(false, 'login failed');
        }
    }

    public function verifyCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'verification_code' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return Response::withoutData(false, $validator->errors()->first());
        }else{
            $user = auth()->user(); // Giriş yapmış kullanıcıyı al
            if (!$user->verificationCodes->isEmpty()) {

                $latestVerificationCode = $user->verificationCodes->last();

                if ($latestVerificationCode->user_verified_at !== null) {
                    return Response::withoutData(false, 'user already verified');
                }else{
                    if ($request->input('verification_code') == $latestVerificationCode->code) {
                        $user->update([
                            'user_verified_at' => Carbon::now(),
                        ]);
                        $latestVerificationCode->delete();

                        return Response::withoutData(true, 'user verified successfully');
                    }
                }
            }
            return Response::withoutData(false, 'verification code is invalid');
        }
    }
}
