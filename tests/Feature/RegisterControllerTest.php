<?php

namespace Tests\Feature;

use App\Http\Resources\RegisterResource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_user_registration_success()
    {
        $userData = [
            'email' => $this->faker->unique()->safeEmail,
            'username' => $this->faker->userName,
            'name' => $this->faker->name,
            'identity_number' => $this->faker->numerify('###########'),
            'birth_day' => $this->faker->date,
            'phone' => $this->faker->numberBetween(1000000000, 9999999999), // Rastgele 10 haneli numara
            'password' => 'Password123', // Set your desired password here
            'password_confirmation' => 'Password123', // Set your desired password here
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user_id',
                    'email',
                    'username',
                    'name',
                    'identity_number',
                    'birth_day',
                    'phone',
                    'user_token',
                    'verification_code',
                ],
            ]);


        $this->assertDatabaseHas('users', [
            'email' => $userData['email'],
            'username' => $userData['username'],
            'name' => $userData['name'],
        ]);
    }

    public function testUserRegistrationValidationFail()
    {
        $invalidUserData = [
            'email' => '', // Eksik email
            'username' => '', // Eksik kullanıcı adı
            'name' => '', // Eksik isim
            'identity_number' => '12345678901', // Hatalı kimlik numarası (11 hane olmalı)
            'birth_day' => '01-12-1999', // Hatalı doğum tarihi
            'phone' => '05554443322', // Hatalı telefon numarası (10 hane olmalı)
            'password' => 'password', // Geçersiz şifre (büyük harf, küçük harf ve sayı içermeli)
            'password_confirmation' => 'password1', // Geçersiz şifre (büyük harf, küçük harf ve sayı içermeli)
        ];


        $response = $this->postJson('/api/register', $invalidUserData);

        $response->assertStatus(422)
            ->assertJson([
                [
                    "success" => false,
                    "message" => "Validation failed",
                    "data" => [
                        "errors" => [
                            "email" => ["The email field is required."],
                            "username" => ["The username field is required."],
                            "name" => ["The name field is required."],
                            "identity_number" => ["The identity number field is required."],
                            "birth_day" => ["The birth day field must be a valid date."],
                            "password" => [
                                "The password field confirmation does not match.",
                                "The password field format is invalid.",
                            ],
                        ],
                    ],
                ],
            ]);

    }
}

