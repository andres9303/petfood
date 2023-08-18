<?php

namespace Database\Seeders;

use App\Models\Catalog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $catalog = Catalog::create([
            'id' => 2,
            'name' => 'Color',
            'text' => 'Colores'
        ]);

        $items = [
            ['id' => 201, 'catalog_id' => $catalog->id,'name' => 'Blanco','text' => 'White','order' => 0],
            ['id' => 202, 'catalog_id' => $catalog->id,'name' => 'Negro','text' => 'Black','order' => 1],
            ['id' => 203, 'catalog_id' => $catalog->id,'name' => 'Amarillo','text' => 'yellow','order' => 2],
            ['id' => 204, 'catalog_id' => $catalog->id,'name' => 'Azul','text' => 'blue','order' => 3],
            ['id' => 205, 'catalog_id' => $catalog->id,'name' => 'Rojo','text' => 'red','order' => 4],
            ['id' => 206, 'catalog_id' => $catalog->id,'name' => 'Verde','text' => 'green','order' => 5],
            ['id' => 207, 'catalog_id' => $catalog->id,'name' => 'Gris','text' => 'gray','order' => 6],
            ['id' => 208, 'catalog_id' => $catalog->id,'name' => 'Morado','text' => 'purple','order' => 7],
            ['id' => 209, 'catalog_id' => $catalog->id,'name' => 'Naranja','text' => 'orange','order' => 8],
        ];

        DB::table('items')->insert($items);

        Catalog::create([
            'id' => 203,
            'name' => 'Categoria producto',
            'text' => 'Categorías de los productos'
        ]);

        Catalog::create([
            'id' => 403,
            'name' => 'Seguimiento mascotas',
            'text' => 'Conceptos de seguimiento mascotas'
        ]);
    }
}
