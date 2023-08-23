<?php

namespace Tests\Feature;

use App\Http\Resources\RegisterResource;
use App\Models\User;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_user_registration_success()
    {
        $password = $this->faker->regexify('^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$');
        $userData = [
            'email' => $this->faker->unique()->safeEmail,
            'username' => $this->faker->userName,
            'name' => $this->faker->name,
            'identity_number' => $this->faker->numberBetween(10000000000, 99999999999),
            'birth_day' => $this->faker->date,
            'phone' => $this->faker->numberBetween(1000000000, 9999999999), // Rastgele 10 haneli numara
            'password' => $password, // Set your desired password here
            'password_confirmation' => $password, // Set your desired password here
        ];

        $response = $this->withHeaders(['accept'=>'application/json'])->post('/api/register', $userData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
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
            'email' => '',
            'username' => '',
            'name' => '',
            'identity_number' => '012233445571',
            'birth_day' => '2024-08-04',
            'phone' => '0554443311',
            'password' => 'Password',
            'password_confirmation' => 'Password1',
        ];

        $response = $this->withHeaders(['accept' => 'application/json'])->post('/api/register', $invalidUserData);

        $expectedErrors = [
            "The email field is required.",
            "The username field is required.",
            "The name field is required.",
            "The identity number field must be 11 digits.",
            "The birth day field must be a date before or equal to today.",
            "The password field confirmation does not match.",
            "The password field format is invalid.",
        ];

        $response->assertStatus(422)
            ->assertJson([
                "message" => "The email field is required. (and 6 more errors)",
                "errors" => [
                    "email" => [$expectedErrors[0]],
                    "username" => [$expectedErrors[1]],
                    "name" => [$expectedErrors[2]],
                    "identity_number" => [$expectedErrors[3]],
                    "birth_day" => [$expectedErrors[4]],
                    "password" => [$expectedErrors[5], $expectedErrors[6]],
                ],
            ]);
    }

}

