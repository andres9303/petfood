<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $user = User::find(auth()->user()->id);
        $roles = $user->roles()->get();

        $shortcuts = Menu::whereIn('id', function($query) use ($roles) {
                            $query->select('menu_id')
                                ->from('shortcuts')
                                ->whereIn('role_id', $roles->pluck('id'));
                        })->distinct()->get();

        return view('dashboard', compact('shortcuts'));
    }
}
