<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        $areas = [
            [
                'name' => 'Desa Cipada',
                'description' => 'Wilayah pemantauan kawasan hutan dan perkebunan.',
                'administrative_region' => 'Kecamatan Cisarua, Kabupaten Bandung Barat',
            ],
            [
                'name' => 'Desa Kertawangi',
                'description' => 'Wilayah pemantauan kawasan perbukitan.',
                'administrative_region' => 'Kecamatan Cisarua, Kabupaten Bandung Barat',
            ],
            [
                'name' => 'Desa Pasirlangu',
                'description' => 'Wilayah pemantauan kawasan permukiman dan lahan terbuka.',
                'administrative_region' => 'Kecamatan Cisarua, Kabupaten Bandung Barat',
            ],
        ];

        foreach ($areas as $area) {
            Area::updateOrCreate(
                ['name' => $area['name']],
                $area
            );
        }
    }
}