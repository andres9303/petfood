<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            ['id' => 100,'coddoc' => null,'text' => 'Seguridad','route' => null, 'active' => null,'icon' => 'fas fa-lock','order' => 0,'menu_id' => null,],
            ['id' => 101,'coddoc' => 'USR','text' => 'Usuarios','route' => 'user', 'active' => 'user*','icon' => 'fas fa-user','order' => 0,'menu_id' => 100,],
            ['id' => 102,'coddoc' => 'GRP','text' => 'Grupos','route' => 'role', 'active' => 'role*','icon' => 'fas fa-users','order' => 1,'menu_id' => 100,],
            ['id' => 103,'coddoc' => 'FRM','text' => 'Formularios','route' => 'menu', 'active' => 'menu*','icon' => 'fas fa-shield-alt','order' => 2,'menu_id' => 100,],
            ['id' => 104,'coddoc' => 'ACD','text' => 'Accesos Directos','route' => 'shortcut', 'active' => 'shortcut*','icon' => 'fas fa-map-signs','order' => 3,'menu_id' => 100,],
            ['id' => 200,'coddoc' => null,'text' => 'Maestros','route' => null, 'active' => null,'icon' => 'fas fa-archive','order' => 1,'menu_id' => null],
            ['id' => 201,'coddoc' => 'UND','text' => 'Unidades','route' => 'unit', 'active' => 'unit*','icon' => 'fas fa-ruler','order' => 1,'menu_id' => 200],
            ['id' => 202,'coddoc' => 'CTP','text' => 'Categorías Productos','route' => 'category', 'active' => 'category*','icon' => 'fas fa-grip-horizontal','order' => 2,'menu_id' => 200],
            ['id' => 203,'coddoc' => 'PRO','text' => 'Productos','route' => 'product', 'active' => 'product*','icon' => 'fab fa-product-hunt','order' => 3,'menu_id' => 200],
            ['id' => 204,'coddoc' => 'DTS','text' => 'Dietas','route' => 'diet', 'active' => 'diet*','icon' => 'fas fa-mortar-pestle','order' => 4,'menu_id' => 200],
            ['id' => 300,'coddoc' => null,'text' => 'Personas','route' => null, 'active' => null,'icon' => 'fas fa-user-friends','order' => 2,'menu_id' => null],
            ['id' => 301,'coddoc' => 'CLI','text' => 'Clientes','route' => 'client', 'active' => 'client*','icon' => 'fas fa-user-tag','order' => 0,'menu_id' => 300],
            ['id' => 302,'coddoc' => 'PVD','text' => 'Proveedores','route' => 'supplier', 'active' => 'supplier*','icon' => 'fas fa-people-carry','order' => 1,'menu_id' => 300],
            ['id' => 400, 'coddoc' => NULL, 'text' => 'Mascotas', 'route' => NULL, 'active' => null, 'icon' => 'fas fa-dog', 'order' => 4, 'menu_id' => NULL],
            ['id' => 401, 'coddoc' => 'TMS', 'text' => 'Tipos Mascotas', 'route' => 'animal', 'active' => 'animal*', 'icon' => 'fas fa-paw', 'order' => 1, 'menu_id' => 400],
            ['id' => 402, 'coddoc' => 'RZA', 'text' => 'Razas', 'route' => 'race', 'active' => 'race*', 'icon' => 'fab fa-ravelry', 'order' => 2, 'menu_id' => 400],
            ['id' => 403, 'coddoc' => 'MSC', 'text' => 'Mascotas', 'route' => 'pet', 'active' => 'pet*', 'icon' => 'fas fa-cat', 'order' => 3, 'menu_id' => 400],
            ['id' => 500, 'coddoc' => NULL, 'text' => 'Gestión pedidos', 'route' => NULL, 'active' => null, 'icon' => 'far fa-handshake', 'order' => 4, 'menu_id' => NULL],
            ['id' => 501, 'coddoc' => 'PED', 'text' => 'Pedidos', 'route' => 'order', 'active' => 'order*', 'icon' => 'fas fa-shopping-cart', 'order' => 1, 'menu_id' => 500],
            ['id' => 502, 'coddoc' => 'PRP', 'text' => 'Preparaciones', 'route' => 'produce', 'active' => 'produce*', 'icon' => 'fas fa-mitten', 'order' => 2, 'menu_id' => 500],
            ['id' => 503, 'coddoc' => 'DSP', 'text' => 'Despacho', 'route' => 'dispatch', 'active' => 'dispatch*', 'icon' => 'fas fa-fax', 'order' => 3, 'menu_id' => 500],            
            ['id' => 600, 'coddoc' => NULL, 'text' => 'Compras', 'route' => NULL, 'active' => null, 'icon' => 'fas fa-boxes', 'order' => 5, 'menu_id' => NULL],
            ['id' => 601, 'coddoc' => 'LCP', 'text' => 'Lista de compras', 'route' => 'shopping-list', 'active' => 'shopping-list*', 'icon' => 'fas fa-clipboard-list', 'order' => 1, 'menu_id' => 600],
            ['id' => 602, 'coddoc' => 'COM', 'text' => 'Compras', 'route' => 'direct-purchase', 'active' => 'direct-purchase*', 'icon' => 'fas fa-shopping-cart', 'order' => 2, 'menu_id' => 600],
            ['id' => 603,'coddoc' => 'BIL','text' => 'Gastos','route' => 'bill', 'active' => 'bill*','icon' => 'fas fa-tags','order' => 3,'menu_id' => 600],
            ['id' => 604, 'coddoc' => 'AJI', 'text' => 'Ajuste de Inventario', 'route' => 'adjustment', 'active' => 'adjustment*', 'icon' => 'fas fa-truck-loading', 'order' => 4, 'menu_id' => 600],
            ['id' => 9000,'coddoc' => NULL,'text' => 'Configuración','route' => NULL, 'active' => null,'icon' => 'fas fa-cog','order' => 9999,'menu_id' => NULL,],
            ['id' => 9001,'coddoc' => 'LST','text' => 'Listas','route' => 'list', 'active' => 'list*','icon' => 'fas fa-list-ol','order' => 1,'menu_id' => 9000,],
            ['id' => 9002,'coddoc' => 'VAR','text' => 'Variables','route' => 'variable', 'active' => 'variable*','icon' => 'fas fa-ruler','order' => 2,'menu_id' => 9000,],
        ];

        DB::table('menus')->insert($menus);
    }
}
