<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Usuario Administrador',
            'username' => 'admin',
            'email' => 'admin@petfood.com',
            'password' => Hash::make('admin'),
        ]);
    }
}
