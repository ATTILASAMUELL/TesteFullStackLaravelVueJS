<?php

namespace Database\Seeders;

use App\Models\Register;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        Register::factory()->count(10)->create();
    }
}
