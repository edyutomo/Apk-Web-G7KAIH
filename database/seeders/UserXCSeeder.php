<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserXCSeeder extends Seeder
{
    public function run()
    {
        $users = [
            ['number' => '3070', 'name' => 'Bahrain Ferlindra'],
            ['number' => '3071', 'name' => 'Chelsea Valenia Cahyono'],
            ['number' => '3072', 'name' => 'Daniellyn Arieta'],
            ['number' => '3073', 'name' => 'David Purnomo Tjhin'],
            ['number' => '3074', 'name' => 'Diandro Nicholas Budiman'],
            ['number' => '3075', 'name' => 'Elaine Audrianna Wijaya'],
            ['number' => '3076', 'name' => 'Elia'],
            ['number' => '3077', 'name' => 'Ethan Pratama Kurniawan'],
            ['number' => '3078', 'name' => 'Evelyn Claryncea Imanuela'],
            ['number' => '3079', 'name' => 'Felicia Veronica Setiawan'],
            ['number' => '3080', 'name' => 'Filbert Bertram Candra'],
            ['number' => '3081', 'name' => 'Gallen Pramana Tio'],
            ['number' => '3082', 'name' => 'Gilbert Joy Maleakhi'],
            ['number' => '3083', 'name' => 'Joanne Shan Wilianto'],
            ['number' => '3084', 'name' => 'Jovan Brilliant Listiyono'],
            ['number' => '3085', 'name' => 'Kelly Roxy Queen'],
            ['number' => '3086', 'name' => 'Maqdalene Wallen'],
            ['number' => '3087', 'name' => 'Michael Adinatha'],
            ['number' => '3088', 'name' => 'Michael Daniswara Kristyatno'],
            ['number' => '3089', 'name' => 'Olyviana Chrisanto'],
            ['number' => '3090', 'name' => 'Petra Agathon Rama Putra'],
            ['number' => '3091', 'name' => 'Samuel Aditjandra Wardjono'],
            ['number' => '3092', 'name' => 'Shellby Maureen Erella Tan'],
            ['number' => '3093', 'name' => 'Sherryl Shane Wijaya'],
            ['number' => '3094', 'name' => 'Steven Wijaya'],
            ['number' => '3095', 'name' => 'Vincentia Stevany'],
        ];

        foreach ($users as $u) {
            $parts = preg_split('/\s+/', trim($u['name']));
            $firstName = $parts[0] ?? 'user';
            $firstClean = preg_replace('/[^A-Za-z]/', '', $firstName);

            $email = $firstClean . $u['number'] . '@sekolah.com';
            $plainPassword = $firstClean . $u['number'] . 'XC';

            if (User::where('email', $email)->exists()) {
                $this->command->info("Skipping existing: {$email}");
                continue;
            }

            User::create([
                'name' => $u['name'],
                'email' => $email,
                'password' => $plainPassword,
                'role' => 'murid',
                'guru_id' => 73,
            ]);

            $this->command->info("Created: {$email} (password: {$plainPassword})");
        }
    }
}