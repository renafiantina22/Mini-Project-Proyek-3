<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Akun Pelapor
        User::updateOrCreate(
            ['email' => 'andi@karhutla.test'],
            [
                'name' => 'Andi',
                'password' => Hash::make('Pelapor123!'),
                'role' => 'pelapor',
            ]
        );

        // Akun Relawan Pemantau
        User::updateOrCreate(
            ['email' => 'sari@karhutla.test'],
            [
                'name' => 'Sari',
                'password' => Hash::make('Relawan123!'),
                'role' => 'relawan',
            ]
        );

        // Akun Koordinator Relawan
        User::updateOrCreate(
            ['email' => 'budi@karhutla.test'],
            [
                'name' => 'Budi',
                'password' => Hash::make('Koordinator123!'),
                'role' => 'koordinator',
            ]
        );
    }
}