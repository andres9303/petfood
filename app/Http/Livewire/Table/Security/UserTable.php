<?php

namespace App\Http\Livewire\Table\Security;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridColumns};

final class UserTable extends PowerGridComponent
{
    use ActionButton;
    use WithExport;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('Usuarios')
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
        return User::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()
            ->addColumn('name')
            ->addColumn('username')
            ->addColumn('email');
    }

    public function columns(): array
    {
        return [
            Column::make('Nombre Usuario', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Login', 'username')
                ->sortable()
                ->searchable(),

            Column::make('EMail', 'email')
                ->sortable()
                ->searchable(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name')->operators(['contains']),
            Filter::inputText('username')->operators(['contains']),
            Filter::inputText('email')->operators(['contains']),
        ];
    }

    public function actions(): array
    {
       return [
           Button::make('edit')
                ->caption('<i class="fa fa-edit mr-1"></i> Editar')
                ->class('inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500')
                ->target('_self')
                ->route('user.edit', function(User $user) {
                    return ['user' => $user->id];
                }),
            Button::add('editPassword')
                ->caption('<i class="fas fa-key mr-1"></i> Contraseña')
                ->tooltip('Cambiar contraseña')
                ->class('inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500')
                ->route('user.editPassword', function(User $user) {
                    return ['user' => $user->id];
                })
                ->target('_self'),
            Button::add('indexRole')
                ->caption('<i class="fa-solid fa-lock mr-1"></i> Grupos')
                ->tooltip('Grupos asociados')
                ->class('inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-yellow-500 border border-transparent rounded-md hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500')
                ->route('user.role.index', function(User $user) {
                    return ['user' => $user->id];
                })
                ->target('_self'),
           Button::make('destroy')
                ->caption('<i class="fa fa-trash-alt mr-1"></i> Eliminar')
                ->class('inline-flex items-center justify-center mt-2 px-4 py-2 text-sm font-medium text-white bg-red-500 border border-transparent rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500')
                ->route('user.destroy', function(User $user) {
                    return ['user' => $user->id];
                })
                ->method('delete')
                ->target('_self')
        ];
    }
    
    public function actionRules(): array
    {
       return [
            Rule::button('destroy')
                ->when(fn($user) => $user->id === auth()->user()->id)
                ->hide(),
        ];
    }
}
