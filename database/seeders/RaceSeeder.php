<?php

namespace Database\Seeders;

use App\Models\Animal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $type = Animal::create([
            'name' => 'Perro',
            'state' => 1
        ]);

        $races = [
            ['name' => 'Labrador Retriever', 'animal_id' => $type->id, 'state' => 1],
            ['name' => 'Pastor Alemán', 'animal_id' => $type->id, 'state' => 1],
            ['name' => 'Bulldog Francés', 'animal_id' => $type->id, 'state' => 1],
            ['name' => 'Golden Retriever', 'animal_id' => $type->id, 'state' => 1],
            ['name' => 'Chihuahua', 'animal_id' => $type->id, 'state' => 1],
            ['name' => 'Pomerania', 'animal_id' => $type->id, 'state' => 1],
            ['name' => 'Dóberman', 'animal_id' => $type->id, 'state' => 1],
            ['name' => 'Pug', 'animal_id' => $type->id, 'state' => 1],
            ['name' => 'Border Collie', 'animal_id' => $type->id, 'state' => 1],
            ['name' => 'Schnauzer', 'animal_id' => $type->id, 'state' => 1],
            ['name' => 'Rottweiler', 'animal_id' => $type->id, 'state' => 1],
            ['name' => 'Boxer', 'animal_id' => $type->id, 'state' => 1],
            ['name' => 'Yorkshire Terrier', 'animal_id' => $type->id, 'state' => 1],
            ['name' => 'Poodle', 'animal_id' => $type->id, 'state' => 1],
            ['name' => 'Dálmata', 'animal_id' => $type->id, 'state' => 1],
        ];

        DB::table('races')->insert($races);

        $type = Animal::create([
            'name' => 'Gato',
            'state' => 1
        ]);

        $races = [
            ['name' => 'Persa', 'animal_id' => $type->id, 'state' => 1],
            ['name' => 'Siamés', 'animal_id' => $type->id, 'state' => 1],
            ['name' => 'Maine Coon', 'animal_id' => $type->id, 'state' => 1],
            ['name' => 'Bengalí', 'animal_id' => $type->id, 'state' => 1],
            ['name' => 'Ragdoll', 'animal_id' => $type->id, 'state' => 1],
            ['name' => 'Sphynx', 'animal_id' => $type->id, 'state' => 1],
            ['name' => 'British Shorthair', 'animal_id' => $type->id, 'state' => 1],
            ['name' => 'Scottish Fold', 'animal_id' => $type->id, 'state' => 1],
            ['name' => 'Siambalés', 'animal_id' => $type->id, 'state' => 1],
            ['name' => 'Azul Ruso', 'animal_id' => $type->id, 'state' => 1],
            ['name' => 'Angora Turco', 'animal_id' => $type->id, 'state' => 1],
            ['name' => 'Manx', 'animal_id' => $type->id, 'state' => 1],
            ['name' => 'Burmés', 'animal_id' => $type->id, 'state' => 1],
            ['name' => 'Abisinio', 'animal_id' => $type->id, 'state' => 1],
            ['name' => 'Himalayo', 'animal_id' => $type->id, 'state' => 1],
        ];        

        DB::table('races')->insert($races);
    }
}
