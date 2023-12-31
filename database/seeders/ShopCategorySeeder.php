<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShopCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('shop_categories')->insert([
            'name' => 'Electronics',
        ]);

        DB::table('shop_categories')->insert([
            'name' => 'Health',
        ]);

        DB::table('shop_categories')->insert([
            'name' => 'Books',
        ]);

        DB::table('shop_categories')->insert([
            'name' => 'Comics',
        ]);

        DB::table('shop_categories')->insert([
            'name' => 'Furniture',
        ]);
    }
}
