<?php

namespace App\Providers;

use App\Actions\Jetstream\DeleteUser;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Jetstream\Jetstream;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configurePermissions();

        Jetstream::deleteUsersUsing(DeleteUser::class);

        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('username', $request->username)->first();

            if ($user &&
                Hash::check($request->password, $user->password)) {

                $roles = $user->roles()->get();

                $ids = collect();
                foreach ($roles as $role) {
                    $ids = $ids->merge($role->menus->pluck('id'));
                    $ids = $ids->merge($role->menus->pluck('menu_id'));
                }

                $menus = Menu::whereIn('id', $ids)
                        ->groupBy('id', 'icon', 'text', 'order', 'route', 'active')
                        ->select('id', 'icon', 'text', 'order', 'route', 'active')
                        ->orderBy('id')
                        ->get();

                $user->menu = $menus->toJson();
                $user->save();

                return $user;
            }
        });
    }

    /**
     * Configure the permissions that are available within the application.
     */
    protected function configurePermissions(): void
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::permissions([
            'create',
            'read',
            'update',
            'delete',
        ]);
    }
}
