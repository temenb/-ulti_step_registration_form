<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            'Afghanistan',
            'Åland Islands',
            'Albania',
            'Algeria',
            'American Samoa',
            'Andorra',
            'Angola',
            'Anguilla',
            'Antarctica',
            'Antigua and Barbuda',
            'Argentina',
            'Armenia',
            'Aruba',
        ];
        foreach ($countries as $country) {
            Country::factory()->create(['name' => $country]);
        }
    }
}
