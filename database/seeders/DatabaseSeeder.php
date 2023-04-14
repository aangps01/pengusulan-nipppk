<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\BerkasPersyaratanSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            BerkasPersyaratanSeeder::class,
            UserSeeder::class,
        ]);

        User::updateOrCreate(
            [
                'nik' => 'admin',
            ],
            [
                'name' => 'Admin',
                'nik' => 'admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('admin'),
                'role' => 2,
            ]
        );
    }
}
