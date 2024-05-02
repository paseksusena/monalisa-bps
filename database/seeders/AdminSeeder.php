<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin1',
            'email' => 'admin@admin1.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
        ]);
    }
}
