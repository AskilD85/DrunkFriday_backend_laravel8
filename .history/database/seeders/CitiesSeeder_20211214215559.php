<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\City;

class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 50; $i++) {
            City::create([
                'name' => Str::random(10),
                'user_id' => 1,
                'active' => 1,
            ]);
    }
}
