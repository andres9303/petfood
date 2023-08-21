<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            ['id' => 1, 'name' => 'Unidad [und]', 'factor' => 1, 'state' => 1, 'unit_id' => 1],
            ['id' => 2, 'name' => 'Segundo [s]', 'factor' => 1, 'state' => 1, 'unit_id' => 2],
            ['id' => 3, 'name' => 'Metro [m]', 'factor' => 1, 'state' => 1, 'unit_id' => 3],
            ['id' => 4, 'name' => 'Kilogramo [kg]', 'factor' => 1, 'state' => 1, 'unit_id' => 4],
            ['id' => 5, 'name' => 'Amperio [A]', 'factor' => 1, 'state' => 1, 'unit_id' => 5],
            ['id' => 6, 'name' => 'Kelvin [K]', 'factor' => 1, 'state' => 1, 'unit_id' => 6],
            ['id' => 7, 'name' => 'Mol [mol]', 'factor' => 1, 'state' => 1, 'unit_id' => 7],
            ['id' => 8, 'name' => 'Candela [cd]', 'factor' => 1, 'state' => 1, 'unit_id' => 8],
            ['id' => 9, 'name' => 'Gramo [gr]', 'factor' => 0.001, 'state' => 1, 'unit_id' => 4],
            ['id' => 10, 'name' => 'Metro cúbico [m3]', 'factor' => 1, 'state' => 1, 'unit_id' => 10],
            ['id' => 11, 'name' => 'Litro [lt]', 'factor' => 0.001, 'state' => 1, 'unit_id' => 10],
            ['id' => 12, 'name' => 'Libra [lb]', 'factor' => 0.5, 'state' => 1, 'unit_id' => 4],
        ];

        DB::table('units')->insert($units);
    }
}
