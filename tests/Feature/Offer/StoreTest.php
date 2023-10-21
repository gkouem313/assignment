<?php

namespace Tests\Feature\Offer;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testWithoutAuthentication()
    {
        // call the api using invalid data
        $response = $this->postJson('/api/offers', [
            'shop_id' => 0,
            'name' => '',
            'description' => '',
        ]);

        // check response
        $response->assertStatus(401);
    }

    public function testStoreOfferWithInvalidData()
    {
        // create a user
        $user = User::factory()->create();
        $this->actingAs($user);

        // call the api using invalid data
        $response = $this->postJson('/api/offers', [
            'shop_id' => 0,
            'name' => '',
            'description' => '',
        ]);

        // check response
        $response->assertStatus(400);
        $response->assertJson(['error_code' => 1]);
    }

    public function testStoreOfferWithNonExistentShop()
    {
        // create a user
        $user = User::factory()->create();
        $this->actingAs($user);

        // call the endpoint using wrong shop_id
        $response = $this->postJson('/api/offers', [
            'shop_id' => 99999, // ERROR
            'name' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
        ]);

        // check the response
        $response->assertStatus(400);
        $response->assertJson(['error_code' => 2]);
    }

    public function testStoreOfferSuccessfully()
    {
        // create a user
        $user = User::factory()->create();
        $this->actingAs($user);

        // create a shop for this user
        $shop = Shop::factory()->create([
            'user_id' => $user->id,
        ]);

        // call the endpoint
        $response = $this->postJson('/api/offers', [
            'shop_id' => $shop->id,
            'name' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
        ]);

        // check the response
        $response->assertStatus(200);
        $response->assertJson(['error_code' => 0]);
    }
}
