<?php

namespace Tests\Feature\Shop;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testWithoutAuthentication()
    {
        // call the endpoint
        $response = $this->patchJson('/api/shops/1', []);

        // check the response
        $response->assertStatus(401);
    }

    public function testValidatorErrors()
    {
        // create a user
        $user = User::factory()->create();
        $this->actingAs($user);

        // create a shop and associate it with the user
        $shop = Shop::factory()->create([
            'user_id' => $user->id,
        ]);

        // call endpoint
        $response = $this->patchJson('/api/shops/' . $shop->id, [
            'name' => '',
        ]);

        // check response
        $response->assertStatus(400);
        $response->assertJson(['error_code' => 1]);
    }

    public function testInvalidShop()
    {
        // Create a user and authenticate them.
        $user = User::factory()->create();
        $this->actingAs($user);

        // call endpoint
        $response = $this->patchJson('/api/shops/99999', []);

        // check response
        $response->assertStatus(400);
        $response->assertJson(['error_code' => 2]);
    }

    public function testInvalidShopCategory()
    {
        // create a user
        $user = User::factory()->create();
        $this->actingAs($user);

        // create a shop and associate it with the user
        $shop = Shop::factory()->create([
            'user_id' => $user->id,
        ]);

        // call endpoint
        $response = $this->patchJson('/api/shops/' . $shop->id, [
            'shop_category_id' => 99999,
        ]);

        // check response
        $response->assertStatus(400);
        $response->assertJson(['error_code' => 3]);
    }

    public function testSuccess()
    {
        // create a user
        $user = User::factory()->create();
        $this->actingAs($user);

        // create a shop and associate it with the authenticated user
        $shop = Shop::factory()->create([
            'user_id' => $user->id,
        ]);

        // call the endpoint
        $response = $this->patchJson('/api/shops/' . $shop->id, [
            'name' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'open_hours' => $this->faker->sentence,
            'city' => $this->faker->city,
            'address' => $this->faker->sentence,
        ]);

        // check the response
        $response->assertStatus(200);
        $response->assertJson(['error_code' => 0]);
    }
}
