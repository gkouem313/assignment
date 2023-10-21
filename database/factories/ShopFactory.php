<?php

namespace Database\Factories;

use App\Models\ShopCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shop>
 */
class ShopFactory extends Factory
{
    use WithFaker;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::factory()->create();
        $shopCategory = ShopCategory::factory()->create();

        return [
            'user_id' => $user->id,
            'shop_category_id' => $shopCategory->id,
            'name' => $this->faker->company,
            'description' => $this->faker->paragraph,
            'open_hours' => $this->faker->sentence,
            'city' => $this->faker->city,
            'address' => $this->faker->optional()->address,
        ];
    }
}
