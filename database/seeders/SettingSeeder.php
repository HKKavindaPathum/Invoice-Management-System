<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::updateOrCreate(
            ['id' => 1],
            [
                'app_name' => 'CellHub Manager',
                'company_name' => 'CellHub Premium Store',
                'company_phone' => '+94 77 123 4567',
                'country' => 'Sri Lanka',
                'state' => 'Western Province',
                'city' => 'Colombo 03',
                'zip_code' => '00300',
                'fax_number' => '+94 11 234 5678',
                'company_address' => 'No. 45, Galle Road, Colombo 03, Sri Lanka',
                'app_logo' => 'uploads/AppLogo/1762874422.png',
            ]
        );
    }
}
