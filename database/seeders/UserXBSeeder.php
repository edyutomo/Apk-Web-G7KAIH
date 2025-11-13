<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserXBSeeder extends Seeder
{
    public function run()
    {
        $users = [
            ['number' => '3044', 'name' => 'Alvin Christian Adianto'],
            ['number' => '3045', 'name' => 'Aurelia Callysta Yuanita'],
            ['number' => '3046', 'name' => 'Azarael Nedavia Timothy Santoso'],
            ['number' => '3047', 'name' => 'Clarisa Adriani Handoyo'],
            ['number' => '3048', 'name' => 'Daniel Kenneth Cristanto'],
            ['number' => '3049', 'name' => 'Daniella Briza Alindra Siswoyo'],
            ['number' => '3050', 'name' => 'Elvina Quintessa Setiawan'],
            ['number' => '3051', 'name' => 'Grace Anggelina Sutejo'],
            ['number' => '3052', 'name' => 'Gracede Maharani Nataly Turnip'],
            ['number' => '3053', 'name' => 'Gracia Miracle Tjin'],
            ['number' => '3054', 'name' => 'Jessica Audrey Nugroho'],
            ['number' => '3055', 'name' => 'Johanna Preciola Dharmanusa'],
            ['number' => '3056', 'name' => 'Justin Elston Saputro'],
            ['number' => '3057', 'name' => 'Kevin Iman Pradana'],
            ['number' => '3058', 'name' => 'Lionel Wilson Cahyadi'],
            ['number' => '3059', 'name' => 'Lionita Selina Willy'],
            ['number' => '3060', 'name' => 'Margareth Enkei Andien Putri'],
            ['number' => '3061', 'name' => 'Matheo Xaviera Aldikaran'],
            ['number' => '3062', 'name' => 'Michelle Lovenia Izhawa Prasetyo'],
            ['number' => '3063', 'name' => 'R.Rr.Fiorenza Neena Aubrey Kusuma'],
            ['number' => '3064', 'name' => 'Ray Daniel Hariyono'],
            ['number' => '3065', 'name' => 'Revaldo Aprillio Putranda'],
            ['number' => '3066', 'name' => 'Tobias Heaven Lando Iskandar'],
            ['number' => '3067', 'name' => 'Wesley Tankianjaya'],
            ['number' => '3068', 'name' => 'Yohan Gregorius Christian'],
            ['number' => '3069', 'name' => 'Yonatan Kristian'],
        ];

        foreach ($users as $u) {
            $parts = preg_split('/\s+/', trim($u['name']));
            $firstName = $parts[0] ?? 'user';
            $firstClean = preg_replace('/[^A-Za-z]/', '', $firstName);

            $email = $firstClean . $u['number'] . '@sekolah.com';
            $plainPassword = $firstClean . $u['number'] . 'XB';

            if (User::where('email', $email)->exists()) {
                $this->command->info("Skipping existing: {$email}");
                continue;
            }

            User::create([
                'name' => $u['name'],
                'email' => $email,
                'password' => $plainPassword,
                'role' => 'murid',
                'guru_id' => 36,
            ]);

            $this->command->info("Created: {$email} (password: {$plainPassword})");
        }
    }
}