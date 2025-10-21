<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admins = [
            ['name' => 'Admin One',   'email' => 'admin1@gmail.com', 'password' => '1111'],
            ['name' => 'Admin Two',   'email' => 'admin2@gmail.com', 'password' => '2222'],
            ['name' => 'Admin Three', 'email' => 'admin3@gmail.com', 'password' => '3333'],
            ['name' => 'Admin Four',  'email' => 'admin4@gmail.com', 'password' => '4444'],
            ['name' => 'Admin Five',  'email' => 'admin5@gmail.com', 'password' => '5555'],
            ['name' => 'Admin Six',   'email' => 'admin6@gmail.com', 'password' => '6666'],
        ];

        foreach ($admins as $admin) {
            User::updateOrCreate(
                ['email' => $admin['email']],
                ['name' => $admin['name'], 'password' => $admin['password']]
            );
        }
    }
}
