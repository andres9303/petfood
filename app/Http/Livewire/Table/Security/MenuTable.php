<?php

namespace App\Http\Livewire\Table\Security;

use App\Models\Menu;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridColumns};

final class MenuTable extends PowerGridComponent
{
    use ActionButton;
    use WithExport;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('Menus')
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
        return Menu::query()
                ->leftJoin('menus as base', function ($menu) {
                    $menu->on('menus.menu_id', '=', 'base.id');
                })->select([
                    'menus.*',
                    'base.text as base_name',
                ]);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()
            ->addColumn('coddoc')
            ->addColumn('text')
            ->addColumn('route')
            ->addColumn('active')
            ->addColumn('icon')
            ->addColumn('base_name');
    }

    public function columns(): array
    {
        return [
            Column::make('Código', 'coddoc', 'menus.coddoc')
                ->sortable()
                ->searchable(),

            Column::make('Formulario', 'text', 'menus.text')
                ->sortable()
                ->searchable(),

            Column::make('Ruta', 'route', 'menus.route')
                ->sortable()
                ->searchable(),

            Column::make('Menú Activo', 'active', 'menus.active')
                ->sortable()
                ->searchable(),

            Column::make('Icono', 'icon', 'menus.icon')
                ->sortable()
                ->searchable(),

            Column::make('Menú Padre', 'base_name', 'base.text')
                ->sortable()
                ->searchable(),

        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('coddoc', 'menus.coddoc')->operators(['contains']),
            Filter::inputText('text', 'menus.text')->operators(['contains']),
            Filter::inputText('route', 'menus.route')->operators(['contains']),
            Filter::inputText('active', 'menus.active')->operators(['contains']),
            Filter::inputText('icon', 'menus.icon')->operators(['contains']),
            Filter::inputText('base_name', 'base.text')->operators(['contains']),
        ];
    }

    public function actions(): array
    {
       return [
            Button::make('edit')
                ->caption('<i class="fa fa-edit mr-1"></i> Editar')
                ->class('inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500')
                ->target('_self')
                ->route('menu.edit', function(Menu $menu) {
                    return ['menu' => $menu->id];
                }),
        ];
    }
}
