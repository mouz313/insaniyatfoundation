<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Area;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $city = City::create(['name' => 'Karachi']);
        Area::create(['city_id' => $city->id, 'name' => 'Saddar']);
        Area::create(['city_id' => $city->id, 'name' => 'Gulshan']);
        Area::create(['city_id' => $city->id, 'name' => 'Clifton']);

        $city = City::create(['name' => 'Lahore']);
        Area::create(['city_id' => $city->id, 'name' => 'Gulberg']);
        Area::create(['city_id' => $city->id, 'name' => 'Johar Town']);

        $city = City::create(['name' => 'Islamabad']);
        Area::create(['city_id' => $city->id, 'name' => 'F-10']);
        Area::create(['city_id' => $city->id, 'name' => 'G-11']);
    }
}
