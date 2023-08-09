<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'Total'],
            ['name' => 'Leer'],
            ['name' => 'Escribir'],
            ['name' => 'Modificar'],
            ['name' => 'Imprimir'],
            ['name' => 'Borrar'],
        ];
        DB::table('permissions')->insert($permissions);
    }
}
