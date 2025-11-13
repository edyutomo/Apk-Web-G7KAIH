<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserXASeeder extends Seeder
{
    public function run()
    {
        $users = [
            ['number' => '3027', 'name' => 'Jazlyn Jefferson Setiawan'],
            ['number' => '3028', 'name' => 'Jolyn Karenza Hardiyanto'],
            ['number' => '3029', 'name' => 'Jordan Felizton Sudarmo'],
            ['number' => '3030', 'name' => 'Joshua Divano Haridandi'],
            ['number' => '3031', 'name' => 'Kinara Avril Sherafina'],
            ['number' => '3032', 'name' => 'Lowrens Jeremia Kawengian'],
            ['number' => '3033', 'name' => 'Magda Chanelysia Evelyn'],
            ['number' => '3034', 'name' => 'Marcell Fabiano Wibowo'],
            ['number' => '3035', 'name' => 'Marchellia Yeung Daoena'],
            ['number' => '3036', 'name' => 'Michael Kurniawan Sanjaya'],
            ['number' => '3037', 'name' => 'Resilia Gabriel Florensia'],
            ['number' => '3038', 'name' => 'Richard Ignazio Harminto'],
            ['number' => '3039', 'name' => 'Rishon Iniko Caesar Hermawan'],
            ['number' => '3040', 'name' => 'Samuel Wilson Kurniawan'],
            ['number' => '3041', 'name' => 'Tesalonika Margaretha Stefany Putri'],
            ['number' => '3042', 'name' => 'Violla Joanna Lausanda'],
            ['number' => '3043', 'name' => 'Yemima Gracia Susilo'],
        ];

        foreach ($users as $u) {
            $parts = preg_split('/\s+/', trim($u['name']));
            $firstName = $parts[0] ?? 'user';
            $firstClean = preg_replace('/[^A-Za-z]/', '', $firstName);

            $email = $firstClean . $u['number'] . '@sekolah.com';
            $plainPassword = $firstClean . $u['number'] . 'XA';

            if (User::where('email', $email)->exists()) {
                $this->command->info("Skipping existing: {$email}");
                continue;
            }

            User::create([
                'name' => $u['name'],
                'email' => $email,
                'password' => $plainPassword, // otomatis di-hash
                'role' => 'murid',
                'guru_id' => 3, // ✅ semua murid diarahkan ke guru_id = 2
            ]);

            $this->command->info("Created: {$email} (password: {$plainPassword})");
        }
    }
}