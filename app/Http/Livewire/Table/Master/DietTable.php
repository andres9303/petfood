<?php

namespace App\Http\Livewire\Table\Master;

use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridColumns};

final class DietTable extends PowerGridComponent
{
    use ActionButton;
    use WithExport;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('Dietas')
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
        return Product::query()
            ->leftJoin('units', 'units.id', '=', 'products.unit_id')
            ->leftJoin('items as class', 'class.id', '=', 'products.class')
            ->where('products.type', 1)
            ->select([
                'products.*',
                'units.name as unit_name',
                'class.name as class_name',
            ]);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()
            ->addColumn('code')
            ->addColumn('name')
            ->addColumn('valueu')
            ->addColumn('valueu_formatted', fn (Product $model) => number_format($model->valueu))
            ->addColumn('unit_name')
            ->addColumn('state')
            ->addColumn('class_name')
            ->addColumn('img', function ($product) {
                return view('components.column-image', [
                    'image' => $product->image,
                ]);
            });

    }

    public function columns(): array
    {
        return [
            Column::make('', 'img')
                ->visibleInExport(false),

            Column::make('Categoría', 'class_name', 'class.name')
                ->sortable()
                ->searchable(),

            Column::make('Código', 'code', 'products.code')
                ->sortable()
                ->searchable(),

            Column::make('Nombre', 'name', 'products.name')
                ->sortable()
                ->searchable(),

            Column::make('Unidad Base', 'unit_name', 'units.name')
                ->sortable()
                ->searchable(),

            Column::make('Precio venta', 'valueu_formatted', 'products.valueu')
                ->sortable()
                ->visibleInExport(false),

            Column::make('Precio venta', 'valueu', 'products.valueu')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Estado', 'state', 'products.state')
                ->sortable()
                ->searchable()
                ->toggleable(false, 'Activo', 'Inactivo'),

        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('class_name', 'class.name')->operators(['contains']),
            Filter::inputText('code', 'products.code')->operators(['contains']),
            Filter::inputText('name', 'products.name')->operators(['contains']),
            Filter::inputText('unit_name', 'units.name')->operators(['contains']),
            Filter::inputText('state', 'products.state')->operators(['contains']),
        ];
    }

    public function actions(): array
    {
       return [
            Button::make('edit')
                ->caption('<i class="fa fa-edit mr-1"></i> Editar')
                ->class('inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500')
                ->target('_self')
                ->route('diet.edit', function(Product $product) {
                    return ['diet' => $product->id];
                }),

            Button::add('image')
                ->caption('<i class="fas fa-camera-retro mr-2"></i> Imagen')
                ->class('inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-yellow-500 border border-transparent rounded-md hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500')
                ->target('_self')
                ->tooltip('Cambiar Imagen')
                ->route('product.edit.image', function(Product $product) {
                    return ['product' => $product->id];
                }),

            Button::add('recipe')
                ->caption('<i class="fas fa-mortar-pestle mr-2"></i> Receta')
                ->class('inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-green-500 border border-transparent rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500')
                ->target('_self')
                ->tooltip('Ingredientes de la receta')
                ->route('diet.recipe.index', function(Product $diet) {
                    return ['diet' => $diet->id];
                }),
        ];
    }
}
