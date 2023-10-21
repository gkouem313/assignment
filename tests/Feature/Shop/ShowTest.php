<?php

namespace Tests\Feature\Shop;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    public function testWithoutAuthentication()
    {
        // call endpoint
        $response = $this->getJson('/api/shops/1');

        // check response
        $response->assertStatus(401);
    }

    public function testInvalidShop()
    {
        // create user
        $user = User::factory()->create();
        $this->actingAs($user);

        // call endpoint
        $response = $this->getJson('/api/shops/99999');

        // check response
        $response->assertStatus(400);
        $response->assertJson(['error_code' => 2]);
    }

    public function testSuccess()
    {
        // create user
        $user = User::factory()->create();
        $this->actingAs($user);

        // create shop and associate it with the user
        $shop = Shop::factory()->create([
            'user_id' => $user->id,
        ]);

        // call endpoint
        $response = $this->getJson('/api/shops/' . $shop->id);

        // check response
        $response->assertStatus(200);
        $response->assertJson(['error_code' => 0]);
    }
}
