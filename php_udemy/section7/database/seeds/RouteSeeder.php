<?php

use Illuminate\Database\Seeder;

class RouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('routes')->insert([
            [
            'id' => 1,
            'name' => '山手線',
            'sort_no' => 1
            ],
            [
            'id' => 2,
            'name' => '京浜東北線',
            'sort_no' => 2
            ],
            [
            'id' => 3,
            'name' => '東武東上線',
            'sort_no' => 3
            ],
        ]);
    }
}
