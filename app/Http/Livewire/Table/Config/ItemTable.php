<?php

namespace App\Http\Livewire\Table\Config;

use App\Models\Item;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridColumns};

final class ItemTable extends PowerGridComponent
{
    use ActionButton;
    use WithExport;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('Listas')
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
        return Item::query()
            ->join('catalogs', 'catalogs.id', '=', 'items.catalog_id')
            ->leftJoin('items as base', 'base.id', '=', 'items.item_id')
            ->where('catalogs.id', '<>', 203)
            ->select([
                'items.*',
                'base.text as base_name',
                'catalogs.name as catalog_name',
            ]);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()
            ->addColumn('catalog_name')
            ->addColumn('name')
            ->addColumn('base_name')
            ->addColumn('text')
            ->addColumn('factor');
    }

    public function columns(): array
    {
        return [
            Column::make('Lista', 'catalog_name', 'catalogs.name')
                ->sortable()
                ->searchable(),

            Column::make('Item', 'name', 'items.name')
                ->sortable()
                ->searchable(),

            Column::make('Base', 'base_name', 'base.name')
                ->sortable()
                ->searchable(),

            Column::make('Texto', 'text', 'items.text')
                ->sortable()
                ->searchable(),

            Column::make('Factor', 'factor', 'items.factor'),

        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('catalog_name', 'catalogs.name')->operators(['contains']),
            Filter::inputText('name', 'items.name')->operators(['contains']),
            Filter::inputText('base_name', 'base.name')->operators(['contains']),
            Filter::inputText('text', 'items.text')->operators(['contains']),
        ];
    }

    public function actions(): array
    {
       return [
            Button::make('edit')
            ->caption('<i class="fa fa-edit mr-1"></i> Editar')
            ->class('inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500')
            ->target('_self')
            ->route('list.edit', function($list) {
                return ['list' => $list->id];
            }),

            Button::make('destroy')
                ->caption('<i class="fa fa-trash-alt mr-1"></i> Eliminar')
                ->class('inline-flex items-center justify-center mt-2 px-4 py-2 text-sm font-medium text-white bg-red-500 border border-transparent rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500')
                ->route('list.destroy', function($list) {
                    return ['list' => $list->id];
                })
                ->method('delete')
                ->target('_self')
        ];
    }
}
