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
        // Call your custom seeder file (UserSeeder)
        $this->call(UserSeeder::class);

        // Demo dashboard content — only seeds an empty database, safe in production.
        $this->call(DemoContentSeeder::class);
    }
}
