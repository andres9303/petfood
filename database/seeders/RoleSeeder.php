<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permission = Permission::where('name', 'Total')->first();
        $user = User::where('username', 'admin')->first();
        $role = Role::create([
            'name' => 'Super Administrador',
        ]);

        $user->roles()->attach($role);
        $menus = Menu::whereNotNull('menu_id')->get();
        foreach ($menus as $menu) {
            $role->permissions()->attach($permission, ['menu_id' => $menu->id]);
        }
    }
}
