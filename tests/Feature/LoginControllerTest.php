<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class LoginControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    public function testUserLoginSuccess()
    {
        // Rastgele veriler oluştur
        $userData = [
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'Password1',
            'name' => $this->faker->name,
            'username' => $this->faker->userName,
            'identity_number' => $this->faker->numberBetween(10000000000, 99999999999),
            'birth_day' => $this->faker->date,
            'phone' => $this->faker->numberBetween(1000000000, 9999999999),
        ];

        // Kullanıcıyı oluştur
        $user = User::create([
            'email' => $userData['email'],
            'password' => bcrypt($userData['password']),
            'name' => $userData['name'],
            'username' => $userData['username'],
            'identity_number' => $userData['identity_number'],
            'birth_day' => $userData['birth_day'],
            'phone' => $userData['phone'],
        ]);

        // Giriş isteği gönder
        $response = $this->withHeaders(['accept' => 'application/json'])->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'Password1',
        ]);

        $responseData = $response->json();
        $userToken = $responseData['data']['user_token'];

        $response->assertStatus(200)
            ->assertJson([
                "status" => true,
                "message" => "user logged in",
                "data" => [
                    "id" => $user->id,
                    "name" => $user->name,
                    "email" => $user->email,
                    "username" => $user->username,
                    "user_token" => $userToken,
                ],
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
                "status" => false,
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

        $response->assertStatus(422)
            ->assertJson([
                "message" => $expectedErrors[0],
            ]);
    }
}

