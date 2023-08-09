<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(MenuSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);        
        $this->call(ItemSeeder::class);
        $this->call(UnitSeeder::class);
        $this->call(RaceSeeder::class);
    }
}
