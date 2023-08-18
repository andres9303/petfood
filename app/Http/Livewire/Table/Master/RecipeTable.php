<?php

namespace App\Http\Livewire\Table\Master;

use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridColumns};

final class RecipeTable extends PowerGridComponent
{
    use ActionButton;
    use WithExport;

    public $diet_id;

    public function setUp(): array
    {
        $this->showCheckBox();
        $diet = Product::find($this->diet_id);

        return [
            Exportable::make('ingredientes_'.$diet->code.'_'.$diet->name)
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
            ->join('recipes', 'recipes.ref_id', '=', 'products.id')
            ->join('units', 'units.id', '=', 'recipes.unit_id')
            ->leftJoin('items as class', 'class.id', '=', 'products.class')
            ->where('recipes.product_id', $this->diet_id)
            ->select([
                'products.*',
                'units.id as unit_recipe_id',
                'units.name as unit_name',
                'class.name as class_name',
                'recipes.cant as cant_recipe',
                'recipes.text as text_recipe',
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
            ->addColumn('cant_recipe')
            ->addColumn('cant_recipe_formatted', fn (Product $model) => number_format($model->cant_recipe, 4))
            ->addColumn('cant', fn (Product $model) => $model->cant_recipe/$model->factor)
            ->addColumn('cant_formatted', fn (Product $model) => number_format($model->cant_recipe/$model->factor, 4))
            ->addColumn('unit_name')
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

            Column::make('Ingrediente', 'name', 'products.name')
                ->sortable()
                ->searchable(),

            Column::make('Unidad', 'unit_name', 'units.name')
                ->sortable()
                ->searchable(),

            Column::make('Cant. preparada', 'cant_recipe_formatted', 'recipes.cant')
                ->sortable()
                ->visibleInExport(false),

            Column::make('Cant. preparada', 'cant_recipe', 'recipes.cant')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Cant. sin preparar', 'cant_formatted')
                ->sortable()
                ->visibleInExport(false),

            Column::make('Cant. sin preparar', 'cant')
                ->visibleInExport(true)
                ->hidden(),

        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('class_name', 'class.name')->operators(['contains']),
            Filter::inputText('code', 'products.code')->operators(['contains']),
            Filter::inputText('name', 'products.name')->operators(['contains']),
            Filter::inputText('unit_name', 'units.name')->operators(['contains']),
        ];
    }

    public function actions(): array
    {
       return [
            Button::make('destroy')
                    ->caption('<i class="fa fa-trash-alt mr-1"></i> Eliminar')
                    ->class('inline-flex items-center justify-center mt-2 px-4 py-2 text-sm font-medium text-white bg-red-500 border border-transparent rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500')
                    ->route('diet.recipe.destroy', function($product) {
                        return ['diet' => $this->diet_id, 'recipe' => $product->id, 'unit' => $product->unit_recipe_id];
                    })
                    ->method('delete')
                    ->target('_self')
        ];
    }
}
