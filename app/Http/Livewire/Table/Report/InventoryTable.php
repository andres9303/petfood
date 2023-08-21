<?php

namespace App\Http\Livewire\Table\Report;

use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridColumns};

final class InventoryTable extends PowerGridComponent
{
    use ActionButton;
    use WithExport;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('Reporte_Saldo_Inventario_'.Carbon::now()->format('d-m-Y_H-i-s'))
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
            ->leftJoin('items as class', 'class.id', '=', 'products.class')
            ->join('mvtos', 'mvtos.product_id', '=', 'products.id')
            ->join('units', 'units.id', '=', 'mvtos.unit2_id')
            ->where('products.isinventory', 1)
            ->where('mvtos.cant2', '<>', 0)
            ->whereNotNull('mvtos.cant2')
            ->orderBy('class.name')->orderBy('products.name')->orderBy('units.name')
            ->groupBy('products.id', 'products.code', 'products.name', 'products.factor', 'units.name', 'class.name')
            ->havingRaw('sum(mvtos.cant2) <> 0 or sum(mvtos.valuet2) <> 0')
            ->select([
                'products.id',
                'products.code',
                'products.name',
                'products.factor',
                'units.name as unit_name',
                'class.name as class_name',
                DB::raw('sum(mvtos.cant2) as cant'),
                DB::raw('sum(mvtos.valuet2) as valuet'),
            ]);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()
            ->addColumn('id')
            ->addColumn('code')
            ->addColumn('name')
            ->addColumn('factor')
            ->addColumn('unit_name')
            ->addColumn('class_name')
            ->addColumn('cant')
            ->addColumn('cant_formatted', fn ($model) => number_format($model->cant, 4))
            ->addColumn('valuet')
            ->addColumn('valuet_formatted', fn ($model) => number_format($model->valuet));
    }

    public function columns(): array
    {
        return [
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

            Column::make('Factor', 'factor', 'products.factor')
                ->sortable()
                ->searchable(),

            Column::make('Existencia', 'cant')
                ->sortable()
                ->searchable()
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Existencia', 'cant_formatted')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Costo Total', 'valuet')
                ->sortable()
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Costo Total', 'valuet_formatted')
                ->sortable()
                ->visibleInExport(false),
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
}
