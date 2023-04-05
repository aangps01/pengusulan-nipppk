<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

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
        ]);

        User::updateOrCreate(
            [
                'nik' => '1234567890123456'
            ],
            [
                'name' => 'Aang Pangantyas Sampurna',
                'nik' => '1234567890123456',
                'email' => '1234567890123456@example.com',
                'password' => bcrypt('1234567890123456'),
                'role' => 1,
            ]
        );

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
