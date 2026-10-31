<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $pelapor = User::where('email', 'andi@karhutla.test')->first();

        if (!$pelapor) {
            throw new \RuntimeException('Akun pelapor belum tersedia.');
        }

        $areas = Area::all();

        if ($areas->isEmpty()) {
            throw new \RuntimeException('Data area belum tersedia.');
        }

        $reports = [
            [
                'title' => 'Asap Terlihat di Area Perbukitan',
                'description' => 'Laporan simulasi adanya asap di sekitar area perbukitan. Memerlukan pemeriksaan lebih lanjut.',
                'incident_time' => now()->subHours(5),
                'latitude' => -6.8000000,
                'longitude' => 107.6000000,
                'condition_level' => 'sedang',
                'status' => 'menunggu_peninjauan',
            ],
            [
                'title' => 'Dugaan Pembakaran Lahan Terbuka',
                'description' => 'Laporan simulasi aktivitas pembakaran pada lahan terbuka.',
                'incident_time' => now()->subHours(8),
                'latitude' => -6.8100000,
                'longitude' => 107.6100000,
                'condition_level' => 'tinggi',
                'status' => 'sedang_ditinjau',
            ],
            [
                'title' => 'Asap Tipis di Sekitar Lahan',
                'description' => 'Laporan simulasi asap tipis yang terlihat dari area permukiman.',
                'incident_time' => now()->subDay(),
                'latitude' => -6.8200000,
                'longitude' => 107.6200000,
                'condition_level' => 'rendah',
                'status' => 'perlu_tindak_lanjut',
            ],
            [
                'title' => 'Pemantauan Area Bekas Pembakaran',
                'description' => 'Laporan simulasi area yang sedang dalam proses tindak lanjut.',
                'incident_time' => now()->subDays(2),
                'latitude' => -6.8300000,
                'longitude' => 107.6300000,
                'condition_level' => 'sedang',
                'status' => 'sedang_ditindaklanjuti',
            ],
            [
                'title' => 'Pemantauan Selesai',
                'description' => 'Laporan simulasi yang proses pemantauan komunitasnya telah selesai.',
                'incident_time' => now()->subDays(3),
                'latitude' => -6.8400000,
                'longitude' => 107.6400000,
                'condition_level' => 'rendah',
                'status' => 'selesai',
            ],
        ];

        foreach ($reports as $index => $report) {
            Report::updateOrCreate(
                ['title' => $report['title']],
                [
                    ...$report,
                    'user_id' => $pelapor->id,
                    'area_id' => $areas[$index % $areas->count()]->id,
                ]
            );
        }
    }
}