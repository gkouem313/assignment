<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testValidatorError()
    {
        // call the endpoint
        $response = $this->postJson('/api/users/login', []);

        // check response
        $response->assertStatus(401);
    }

    public function testSuccess()
    {
        // create user
        $user = User::factory()->create([
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
        ]);

        // create credentials fro basic auth
        $credentials = base64_encode($user->email . ':' . 'password');

        // call the endpoint
        $response = $this->withHeaders([
            'Authorization' => 'Basic ' . $credentials,
        ])->postJson('/api/users/login', [
            'name' => $user->name
        ]);

        // check response
        $response->assertStatus(200);
        $response->assertJson(['error_code' => 0]);
    }
}
