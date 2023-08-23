<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;


class LoginControllerTest extends TestCase
{
    public function testUserLoginSuccess()
    {
        $userData = [
            'email' => 'test@example.com',
            'password' => 'Password1',
            'name' => 'John Doe', // Örnek bir isim
            'username' => 'johndoe', // Örnek bir kullanıcı adı
            'identity_number' => '12345678901', // Örnek kimlik numarası
            'birth_day' => '2000-01-01', // Örnek doğum tarihi
            'phone' => '5555555555', // Örnek telefon numarası
        ];

        $user = User::create([
            'email' => $userData['email'],
            'password' => bcrypt($userData['password']),
            'name' => $userData['name'],
            'username' => $userData['username'],
            'identity_number' => $userData['identity_number'],
            'birth_day' => $userData['birth_day'],
            'phone' => $userData['phone'],
        ]);


        $response = $this->withHeaders(['accept'=>'application/json'])->post('/api/login', [
            'email' => $userData['email'],
            'password' => $userData['password'],
        ]);

        $response->assertStatus(200)
            ->assertJson([
                "success" => true,
                "message" => "user logged in",
                "data" => [
                    "id" => $user->id,
                    "name" => $user->name,
                    "email" => $user->email,
                    "username" => $user->username,
                    "user_token" => $user->tokens->first()->plainTextToken,                ],
            ]);

    }

    public function testUserLoginFailed()
    {
        $userData = [
            'email' => 'test@example.com',
            'password' => 'WrongPassword',
        ];

        $response = $this->withHeaders(['accept'=>'application/json'])->post('/api/login', $userData);

        $response->assertStatus(200)
            ->assertJson([
                "success" => false,
                "message" => "login failed",
            ]);
    }


    public function testUserLoginValidationFail()
    {
        $invalidUserData = [
            'email' => 'invalid_email', // Geçersiz email formatı
            'password' => 'short', // Geçersiz şifre uzunluğu
        ];

        $response = $this->withHeaders(['accept'=>'application/json'])->post('/api/login', $invalidUserData);

        $expectedErrors = [
            "The email field must be a valid email address.",
            "The password field must be at least 8 characters.",
        ];

        $response->assertStatus(200)
            ->assertJson([
                "success" => false,
                "message" => $expectedErrors[0],
            ]);
    }
}

