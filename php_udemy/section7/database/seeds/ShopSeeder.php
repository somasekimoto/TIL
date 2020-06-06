<?php

use Illuminate\Database\Seeder;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('shops')->insert([
            [
            'id' => 1,
            'shop_name' => '高級食パン',
            'area_id' => 1
            ],
            [
            'id' => 2,
            'shop_name' => '高級コッペパン',
            'area_id' => 2
            ],
            [
            'id' => 3,
            'shop_name' => 'クロワッサン屋',
            'area_id' => 1
            ],
            [
            'id' => 4,
            'shop_name' => '高級メロンパン屋',
            'area_id' => 3
            ],
        ]);
    }
}
