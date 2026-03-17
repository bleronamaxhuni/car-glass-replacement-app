<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GlassType;

class GlassTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'Front Windshield',
            'Rear Windshield',
            'Sunroof',
            'Front Right Door Glass',
            'Front Left Door Glass',
            'Rear Right Door Glass',
            'Rear Left Door Glass',
        ];

        foreach ($types as $type) {
            GlassType::firstOrCreate(['name' => $type]);
        }
    }
}
