<?php

namespace App\Http\Livewire\Table\Security;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridColumns};

final class UserRoleTable extends PowerGridComponent
{
    use ActionButton;
    use WithExport;

    public int $user;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('Roles_Usuario')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showToggleColumns(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return User::query()
            ->join('user_role', 'users.id', '=', 'user_role.user_id')
            ->join('roles', 'user_role.role_id', '=', 'roles.id')
            ->where('users.id', $this->user)
            ->select('users.id', 
                'roles.name as name_role', 
                'user_role.role_id',
                'users.name as name_user'
            );
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()
            ->addColumn('name_user')
            ->addColumn('name_role');
    }

    public function columns(): array
    {
        return [
            Column::make('Usuario', 'name_user', 'users.name')
                ->sortable()
                ->searchable(),

            Column::make('Grupos', 'name_role', 'roles.name')
                ->sortable()
                ->searchable(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name_user', 'users.name')->operators(['contains']),
            Filter::inputText('name_role', 'roles.name')->operators(['contains']),
        ];
    }

    public function actions(): array
    {
       return [
            Button::make('destroy')
                ->caption('<i class="fa fa-trash-alt mr-1"></i> Eliminar')
                ->class('inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-red-500 border border-transparent rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500')
                ->route('user.role.destroy', function($user) {
                    return ['user' => $user->id, 'role' => $user->role_id];
                })
                ->method('delete')
                ->target('_self')
        ];
    }
}
