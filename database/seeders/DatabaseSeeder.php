<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // Memanggil UserSeeder untuk mengisi data user & admin
        $this->call(UserSeeder::class);
    }
}
