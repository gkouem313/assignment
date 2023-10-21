<?php

namespace Tests\Feature\Shop;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditTest extends TestCase
{
    use RefreshDatabase;

    public function testWithoutAuthentication()
    {
        // call endpoint
        $response = $this->getJson('/api/shops/1/edit');

        // check response
        $response->assertStatus(401);
    }

    public function testInvalidShopId()
    {
        // create user
        $user = User::factory()->create();
        $this->actingAs($user);

        // call endpoint
        $response = $this->getJson('/api/shops/99999/edit');

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
        $response = $this->getJson('/api/shops/' . $shop->id . '/edit');

        // return response
        $response->assertStatus(200);
        $response->assertJson(['error_code' => 0]);
    }
}
