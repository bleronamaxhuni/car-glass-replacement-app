<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CarBodyType;

class CarBodyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'Sedan',
            'Hatchback',
            'SUV',
            'MPV',
            'Coupe',
            'Convertible',
            'Pickup',
            'Van',
            'Bus',
        ];

        foreach ($types as $type) {
            CarBodyType::firstOrCreate(['name' => $type]);
        }
    }
}
