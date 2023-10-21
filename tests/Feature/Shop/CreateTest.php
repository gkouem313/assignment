<?php

namespace Tests\Feature\Shop;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    public function testWithoutAuthentication()
    {
        // call endpoint
        $response = $this->getJson('/api/shops/create');

        // check response
        $response->assertStatus(401);
    }

    public function testSuccess()
    {
        // create user
        $user = User::factory()->create();
        $this->actingAs($user);

        // call endpoint
        $response = $this->getJson('/api/shops/create');

        // check response
        $response->assertStatus(200);
        $response->assertJson(['error_code' => 0]);
    }
}
