<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class StoreUserTest extends TestCase
{
    use RefreshDatabase;


    public function test_if_user_can_register()
    {
        $userData = [
            'name' => 'John',
            'email' => 'john@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->postJson('/api/register', $userData);

        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'auth_token',
                'user' => [
                    'id',
                    'name',
                    'email',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'John',
            'email' => 'john@gmail.com',
        ]);
    }

    public function test_if_user_can_register_with_not_matching_password()
    {
        $userData = [
            'name' => 'John',
            'email' => 'john@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password1',
        ];

        $response = $this->postJson('/api/register', $userData);

        $response
            ->assertStatus(403)
            ->assertJsonStructure([
                'error',
            ]);

        $this->assertDatabaseMissing('users', [
            'name' => 'John',
            'email' => 'john@gmail.com',
        ]);
    }

    public function test_if_user_can_login()
    {
        User::factory()->create([
            'email' => 'john@gmail.com',
            'password' => bcrypt('password'),
        ]);

        $loginData = [
            'email' => 'john@gmail.com',
            'password' => 'password',
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'auth_token',
                'user' => [
                    'id',
                    'name',
                    'email'
                ],
            ]);

        $this->assertAuthenticated();
    }

    public function test_if_user_can_login_with_wrong_password()
    {
        User::factory()->create([
            'email' => 'john@gmail.com',
            'password' => bcrypt('password'),
        ]);

        $loginData = [
            'email' => 'john@gmail.com',
            'password' => 'password1',
        ];

        $response = $this->postJson('/api/login', $loginData);

        $response
            ->assertStatus(401)
            ->assertJsonStructure([
                'message',
            ]);

        $this->assertGuest();
    }

    public function test_if_user_can_logout()
    {
        User::factory()->create([
            'email' => 'john@gmail.com',
            'password' => bcrypt('password')
        ]);

        $loginData = [
            'email' => 'john@gmail.com',
            'password' => 'password',
        ];

        $loginResponse = $this->postJson('/api/login', $loginData);

        $token = $loginResponse->json('auth_token');

        $logoutResponse = $this->withHeaders([
            'Authorization' => 'Bearer',
            $token,
        ])->postJson('/api/logout');

        $logoutResponse
            ->assertStatus(200)
            ->assertJson([
                'message' => 'Successfully logged out'
            ]);
    }
}
