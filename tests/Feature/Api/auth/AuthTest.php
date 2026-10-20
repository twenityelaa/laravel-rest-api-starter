<?php

namespace Tests\Feature\Api\auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */

    // test user dapat login
    public function test_user_dapat_login(): void
    {
        // arrange
        $user = User::factory()->create();

        // act
        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        // assert
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'token',
            'user' => [
                'id',
                'name',
                'email',
                'created_at',
                'updated_at',
            ]
        ]);
    }

    // test user tidak dapat login dengan kredensial salah
    public function test_user_tidak_dapat_login(): void
    {
        // arrange
        $user = User::factory()->create();

        // act
        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        // assert
        $response->assertStatus(401);
        $response->assertJson(['message' => 'Invalid credentials']);
    }

    // test user dapat register
    public function test_user_dapat_register(): void
    {
        // arrange
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ];

        // act
        $response = $this->postJson('/api/auth/register', $userData);

        // assert
        $response->assertCreated();
        $this->assertDatabaseHas('users', [
            'email' => $userData['email'],
        ]);
    }

    // test user dapat logout
    public function test_user_dapat_logout(): void
    {
        // arrange
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        // act
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/auth/logout');

        // assert
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Successfully logged out',
        ]);
    }
}
