<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GlassType;
use App\Models\Vendor;
use App\Models\VendorGlassPrice;

class VendorGlassPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $glassTypes = GlassType::all();
        $vendors = Vendor::all();

        foreach ($glassTypes as $glassType) {
            foreach ($vendors as $vendor) {

                $base = match ($glassType->name) {
                    'Windshield' => 250,
                    'Rear Glass' => 220,
                    'Front Right Door Glass' => 180,
                    'Front Left Door Glass' => 180,
                    'Rear Right Door Glass' => 180,
                    'Rear Left Door Glass' => 180,
                    'Sunroof' => 300,
                    default => 150,
                };
                $price = $base + rand(-30, 30);
                $warrantyMonths = rand(6, 24);
                $deliveryDays = rand(1, 7);
                VendorGlassPrice::firstOrCreate(
                    [
                        'vendor_id' => $vendor->id,
                        'glass_type_id' => $glassType->id,
                    ],
                    [
                        'price' => $price,
                        'warranty_time' => $warrantyMonths,
                        'delivery_time' => $deliveryDays,
                    ]
                );
            }
        }
    }
}