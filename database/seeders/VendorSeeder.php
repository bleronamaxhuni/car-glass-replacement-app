<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vendor;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendors = [
            'QuickFix Glass',
            'Premium Auto Glass',
            'Express Auto Glass',
            'Auto Glass Now',
            'Auto Glass Pro',
        ];

        foreach ($vendors as $vendor) {
            Vendor::firstOrCreate(['name' => $vendor]);
        }
    }
}
