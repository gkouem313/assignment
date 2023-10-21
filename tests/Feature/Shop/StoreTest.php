<?php

namespace Tests\Feature\Shop;

use App\Models\ShopCategory;
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
        // call endpoint
        $response = $this->postJson('/api/shops');

        // check response
        $response->assertStatus(401);
    }

    public function testValidatorErrors()
    {
        // create user
        $user = User::factory()->create();
        $this->actingAs($user);

        // call endpoint
        $response = $this->postJson('/api/shops', [
            'shop_category_id' => '',
            'name' => '',
            'description' => '',
            'open_hours' => '',
            'city' => '',
            'address' => '',
        ]);

        $response->assertStatus(400);
        $response->assertJson(['error_code' => 1]);
    }

    public function testInvalidCategory()
    {
        // create user
        $user = User::factory()->create();
        $this->actingAs($user);

        // call endpoint
        $response = $this->postJson('/api/shops', [
            'shop_category_id' => 99999,
            'name' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'open_hours' => $this->faker->sentence,
            'city' => $this->faker->city,
            'address' => $this->faker->sentence,
        ]);

        $response->assertStatus(400);
        $response->assertJson(['error_code' => 2]);
    }

    public function testSuccess()
    {
        // create shop category
        $shopCategory = ShopCategory::factory()->create();

        // create user
        $user = User::factory()->create();
        $this->actingAs($user);

        // call endpoint
        $response = $this->postJson('/api/shops', [
            'shop_category_id' => $shopCategory->id,
            'name' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'open_hours' => $this->faker->sentence,
            'city' => $this->faker->city,
            'address' => $this->faker->sentence,
        ]);

        // check response
        $response->assertStatus(200);
        $response->assertJson(['error_code' => 0]);
    }
}
