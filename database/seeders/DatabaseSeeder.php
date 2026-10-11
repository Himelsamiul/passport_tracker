<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Master admin only (shahriar.agroup@gmail.com) + starter roles
        $this->call(UserSeeder::class);
        $this->call(RolesSeeder::class);

        // Demo dashboard content — only seeds an empty database, safe in production.
        $this->call(DemoContentSeeder::class);
    }
}
