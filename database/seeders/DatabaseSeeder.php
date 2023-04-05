<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

        \App\Models\User::factory()->create([
            'name' => 'Aang Pangantyas Sampurna',
            'nik' => '1234567890123456',
            'email' =>'1234567890123456@example.com',
            'password' => bcrypt('1234567890123456'),
            'role' => 1,
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'nik' => 'admin',
            'email' =>'admin@example.com',
            'password' => bcrypt('admin'),
            'role' => 2,
        ]);
    }
}
