<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Area;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $city = City::firstOrCreate(['name' => 'Karachi']);
        Area::firstOrCreate(['city_id' => $city->id, 'name' => 'Saddar']);
        Area::firstOrCreate(['city_id' => $city->id, 'name' => 'Gulshan']);
        Area::firstOrCreate(['city_id' => $city->id, 'name' => 'Clifton']);

        $city = City::firstOrCreate(['name' => 'Lahore']);
        Area::firstOrCreate(['city_id' => $city->id, 'name' => 'Gulberg']);
        Area::firstOrCreate(['city_id' => $city->id, 'name' => 'Johar Town']);

        $city = City::firstOrCreate(['name' => 'Islamabad']);
        Area::firstOrCreate(['city_id' => $city->id, 'name' => 'F-10']);
        Area::firstOrCreate(['city_id' => $city->id, 'name' => 'G-11']);

        $cities = [
            // Sindh
            'Hyderabad', 'Sukkur', 'Larkana', 'Nawabshah', 'Mirpur Khas',
            'Khairpur', 'Shikarpur', 'Jacobabad', 'Dadu', 'Badin',
            'Thatta', 'Sanghar', 'Umerkot', 'Tando Allahyar', 'Tando Muhammad Khan',
            'Ghotki', 'Kashmore', 'Naushahro Feroze', 'Qambar Shahdadkot',

            // Punjab
            'Faisalabad', 'Rawalpindi', 'Gujranwala', 'Multan', 'Bahawalpur',
            'Sargodha', 'Sialkot', 'Sheikhupura', 'Rahim Yar Khan', 'Jhang',
            'Dera Ghazi Khan', 'Gujrat', 'Sahiwal', 'Kasur', 'Okara',
            'Vehari', 'Chiniot', 'Hafizabad', 'Mandi Bahauddin', 'Narowal',
            'Nankana Sahib', 'Layyah', 'Bhakkar', 'Khushab', 'Mianwali',
            'Muzaffargarh', 'Rajanpur', 'Lodhran', 'Khanewal', 'Pakpattan',
            'Jhelum', 'Chakwal', 'Attock', 'Murree', 'Talagang',

            // Khyber Pakhtunkhwa
            'Peshawar', 'Mardan', 'Abbottabad', 'Mingora', 'Kohat',
            'Bannu', 'Dera Ismail Khan', 'Charsadda', 'Swabi', 'Nowshera',
            'Mansehra', 'Haripur', 'Chitral', 'Hangu', 'Karak',
            'Lakki Marwat', 'Tank', 'Buner', 'Batkhela', 'Kohistan',

            // Balochistan
            'Quetta', 'Turbat', 'Gwadar', 'Khuzdar', 'Chaman',
            'Sibi', 'Zhob', 'Loralai', 'Dera Murad Jamali', 'Nushki',
            'Kalat', 'Panjgur', 'Mastung', 'Pishin', 'Qila Saifullah',

            // Gilgit-Baltistan
            'Gilgit', 'Skardu', 'Hunza', 'Chilas', 'Khaplu',
            'Shigar', 'Gahkuch', 'Eidgah', 'Kharmang',

            // Azad Jammu & Kashmir
            'Muzaffarabad', 'Mirpur', 'Kotli', 'Rawalakot', 'Bhimber',
            'Bagh', 'Pallandri', 'Hattian Bala', 'Athmuqam',
        ];

        foreach ($cities as $cityName) {
            City::firstOrCreate(['name' => $cityName]);
        }
    }
}
