<?php

namespace App\Http\Livewire\Table\Master;

use App\Models\Item;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridColumns};

final class CategoryTable extends PowerGridComponent
{
    use ActionButton;
    use WithExport;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('Categorias')
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
            ->where('catalog_id', 203);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()
            ->addColumn('name')
            ->addColumn('text')
            ->addColumn('order');
    }

    public function columns(): array
    {
        return [
            Column::make('Nombre Categoría', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Observación', 'text')
                ->sortable()
                ->searchable(),

            Column::make('Orden', 'order'),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name', 'name')->operators(['contains']),
            Filter::inputText('text', 'text')->operators(['contains']),
            Filter::inputText('order', 'order')->operators(['contains']),
        ];
    }

    public function actions(): array
    {
       return [
            Button::make('edit')
                ->caption('<i class="fa fa-edit mr-1"></i> Editar')
                ->class('inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500')
                ->target('_self')
                ->route('category.edit', function(Item $item) {
                    return ['category' => $item->id];
                }),

            Button::make('destroy')
                ->caption('<i class="fa fa-trash-alt mr-1"></i> Eliminar')
                ->class('inline-flex items-center justify-center mt-2 px-4 py-2 text-sm font-medium text-white bg-red-500 border border-transparent rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500')
                ->route('category.destroy', function(Item $item) {
                    return ['category' => $item->id];
                })
                ->method('delete')
                ->target('_self')
        ];
    }
    
    public function actionRules(): array
    {
       return [
            Rule::button('destroy')
                ->when(fn($item) => $item->products()->count() > 0)
                ->hide(),
        ];
    }
}
