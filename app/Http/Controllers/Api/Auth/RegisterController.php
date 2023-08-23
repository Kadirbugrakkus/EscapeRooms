<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Response;
use App\Http\Requests\RegisterValidationRequest;
use App\Http\Resources\RegisterResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(RegisterValidationRequest $request)
    {
        $validator = Validator::make($request->all(), (new RegisterValidationRequest())->rules());

        if ($validator->fails()) {
            return Response::withoutData(false, $validator->errors()->first());
        } else {
            $user = User::create([
                'email' => $request->input('email'),
                'username' => $request->input('username'),
                'name' => $request->input('name'),
                'identity_number' => $request->input('identity_number'),
                'birth_day' => $request->input('birth_day'),
                'phone' => $request->input('phone'),
                'password' => Hash::make($request->input('password'))
            ]);

            if ($user) {
                $verificationCode = mt_rand(100000, 999999);
                $user->verificationCodes()->create([
                    'code' => $verificationCode
                ]);

                $token = $user->createToken('auth_token')->plainTextToken;

                $userResource = new RegisterResource($user);
                $userResource->user_token = $token;
                $userResource->verification_code = $verificationCode;

                return Response::withData(true, 'user created', $userResource);
            } else {
                return Response::withoutData(false, 'user not created');
            }
        }
    }

}
