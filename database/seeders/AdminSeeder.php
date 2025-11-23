<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    public function run()
    {
        Admin::updateOrCreate(
            ['username' => 'admin'], // cek berdasarkan username biar nggak double

            [
                'password' => Hash::make('admin123'),
                'email' => 'admin@gmail.com',
                'nama' => 'Administrator',
                'foto' => null,
            ]
        );
    }
}
