<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('shops')->insert([
            'user_id' => 1,
            'shop_category_id' => 1,
            'name' => 'Asterix',
            'description' => 'This is the first shop.',
            'open_hours' => '9:00 AM - 6:00 PM',
            'city' => 'Athens',
            'address' => 'Armoriki 19',
        ]);

        DB::table('shops')->insert([
            'user_id' => 1,
            'shop_category_id' => 2,
            'name' => 'One Piece',
            'description' => 'This is the second shop.',
            'open_hours' => '8:00 AM - 7:00 PM',
            'city' => 'Thessaloniki',
            'address' => 'Megali Grammi 12',
        ]);

        DB::table('shops')->insert([
            'user_id' => 2,
            'shop_category_id' => 3,
            'name' => 'Naruto',
            'description' => 'This is the third shop.',
            'open_hours' => '10:00 AM - 8:00 PM',
            'city' => 'Ioannina',
        ]);

        DB::table('shops')->insert([
            'user_id' => 3,
            'shop_category_id' => 4,
            'name' => 'Lucky Luke',
            'description' => 'This is the fourth shop.',
            'open_hours' => '9:00 AM - 5:00 PM',
            'city' => 'Heraklion',
            'address' => 'Abiline 66',
        ]);
    }
}
