<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        User::create([
            'username' => 'kadis',
            'email' => 'kadis@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'kadis'
        ]);

        User::create([
            'username' => 'kasi',
            'email' => 'kasi@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'kasi'
        ]);

        User::create([
            'username' => 'kabid',
            'email' => 'kabid@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'kabid'
        ]);
    }
}
