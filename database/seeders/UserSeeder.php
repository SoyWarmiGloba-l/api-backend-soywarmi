<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'role' => 'Admin',
            'name' => 'Admin 1',
            'email' => 'email1@gmail.com',
            'password' => Hash::make('123456'),
        ]);
    }
}
