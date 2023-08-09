<?php

namespace App\Policies;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MenuPolicy
{
    use HandlesAuthorization;

    public function view(User $user, $name): bool
    {
        $menu = Menu::where('route', $name)->first();
        if($menu)
        {
            $roles = $user->roles()->get();
            foreach ($roles as $role) {
                if ($role->menus->contains('id', $menu->id)) {
                    return true;
                }
            }
        }

        return false;
    }
}
