<?php

namespace App\Http\Controllers\security;

use App\Actions\Fortify\PasswordValidationRules;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    use PasswordValidationRules;
    
    public function index()
    {
        return view('security.user.index');
    }

    public function create()
    {
        return view('security.user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
        ]);

        DB::beginTransaction();
        try {
            User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            DB::commit();
            return redirect()->route('user.index')->with('success', 'Se ha registrado el usuario correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('user.index')->with('error', 'Ha ocurrido un error al registrar el usuario.');
        }
    }

    public function edit(User $user)
    {
        return view('security.user.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        DB::beginTransaction();
        try {
            $user->name = $request->name;
            $user->username = $request->username;
            $user->email = $request->email;

            $user->save();

            DB::commit();
            return redirect()->route('user.index')->with('success', 'Se ha actualizado la información del usuario correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('user.index')->with('error', 'Ha ocurrido un error al actualizar la información del usuario.');
        }
    }

    public function destroy(User $user)
    {
        DB::beginTransaction();
        try {
            $user->delete();

            DB::commit();
            return redirect()->route('user.index')->with('success', 'Se ha eliminado el usuario correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('user.index')->with('error', 'Ha ocurrido un error al eliminar el usuario.');
        }
    }

    public function editPassword(User $user)
    {
        return view('security.user.edit-password', compact('user'));
    }

    public function updatePassword(Request $request, User $user)
    {
        $request->validate([
            'password' => $this->passwordRules(),
        ]);

        DB::beginTransaction();
        try {
            $user->password = Hash::make($request->password);            
            $user->save();

            DB::commit();
            return redirect()->route('user.index')->with('success', 'Se ha actualizado la contraseña del usuario correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('user.index')->with('error', 'Ha ocurrido un error al actualizar la contraseña del usuario.');
        }
    }

    public function indexRole(User $user)
    {
        return view('security.user.role.index', compact('user'));
    }

    public function createRole(User $user)
    {
        $roles = Role::all();

        return view('security.user.role.create', compact('user', 'roles'));
    }

    public function storeRole(Request $request, User $user)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        DB::beginTransaction();
        try {
            if ($user->roles()->where('role_id', $request->role_id)->exists()) {
                session()->flash('error', 'El permiso ya existe.');
                return redirect()->back();
            }

            $user->roles()->attach($request->role_id);

            DB::commit();
            return redirect()->route('user.role.index', ['user' => $user])->with('success', 'Se ha registrado el grupo correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('user.role.index', ['user' => $user])->with('error', 'Ha ocurrido un error al registrar el grupo.');
        }
    }

    public function destroyRole(User $user, Role $role)
    {
        DB::beginTransaction();
        try {
            $user->roles()
                ->detach($role->id);

            DB::commit();
            return redirect()->route('user.role.index', ['user' => $user])->with('success', 'Se ha eliminado el permiso correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('user.role.index', ['user' => $user])->with('error', 'Ha ocurrido un error al eliminar el permiso.');
        }
    }
}
