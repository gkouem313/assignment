<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testValidatorError()
    {
        // call the endpoint
        $response = $this->postJson('/api/users/register', [
            'name' => '',
            'email' => 'invalid-email',
            'password' => 'ERROR', // ERROR
        ]);

        // check response
        $response->assertStatus(400);
        $response->assertJson(['error_code' => 1]);
    }

    public function testSuccess()
    {
        // call the endpoint
        $response = $this->postJson('/api/users/register', [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => $this->faker->password(8, 20),
        ]);

        // check response
        $response->assertStatus(200);
        $response->assertJson(['error_code' => 0]);
    }
}
