<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Buat Pengawas
        $pengawas = User::create([
            'name' => 'Pak Pengawas',
            'email' => 'pengawas@sekolah.com',
            'password' => Hash::make('password'),
            'role' => 'pengawas'
        ]);

        $pengawas = User::create([
            'name' => 'Novian Ariyan',
            'email' => 'novian@sekolah.com',
            'password' => Hash::make('password'),
            'role' => 'pengawas'
        ]);

        // Buat Guru
        $guru = User::create([
            'name' => 'Bu Guru',
            'email' => 'guru@sekolah.com', 
            'password' => Hash::make('password'),
            'role' => 'guru'
        ]);

    }
}
