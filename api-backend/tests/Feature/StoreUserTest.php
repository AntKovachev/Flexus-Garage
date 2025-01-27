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
}
