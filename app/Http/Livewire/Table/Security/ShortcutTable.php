<?php

namespace App\Http\Livewire\Table\Security;

use App\Models\Role;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridColumns};

final class ShortcutTable extends PowerGridComponent
{
    use ActionButton;
    use WithExport;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('export')
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
        return Role::query()
            ->join('shortcuts', 'roles.id', '=', 'shortcuts.role_id')
            ->join('menus', 'shortcuts.menu_id', '=', 'menus.id')
            ->select('roles.id', 
                'roles.name as name_role', 
                'shortcuts.menu_id',
                'menus.text as menu_text'
            );
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()
            ->addColumn('name_role')
            ->addColumn('menu_text');
    }

    public function columns(): array
    {
        return [
            Column::make('Grupo', 'name_role', 'roles.name')
                ->sortable()
                ->searchable(),

            Column::make('Formulario', 'menu_text', 'menus.text')
                ->sortable()
                ->searchable(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name_role', 'roles.name')->operators(['contains']),
            Filter::inputText('menu_text', 'menus.text')->operators(['contains']),
        ];
    }

    public function actions(): array
    {
       return [
        Button::make('destroy')
            ->caption('<i class="fa fa-trash-alt mr-1"></i> Eliminar')
            ->class('inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-red-500 border border-transparent rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500')
            ->route('shortcut.destroy', function($role) {
                return ['shortcut' => $role->id, 'menu_id' => $role->menu_id];
            })
            ->method('delete')
            ->target('_self')
        ];
    }
}
