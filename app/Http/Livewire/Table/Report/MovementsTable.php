<?php

namespace App\Http\Livewire\Table\Report;

use App\Models\Mvto;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridColumns};

final class MovementsTable extends PowerGridComponent
{
    use ActionButton;
    use WithExport;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('Movimientos_Inventario_'.Carbon::now()->format('d-m-Y_H-i-s'))
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
        return Mvto::query()
            ->join('docs', 'mvtos.doc_id', '=', 'docs.id')
            ->join('menus', 'docs.menu_id', '=', 'menus.id')
            ->join('products', 'mvtos.product_id', '=', 'products.id')
            ->leftJoin('items as class', 'class.id', '=', 'products.class')
            ->join('units', 'units.id', '=', 'mvtos.unit_id')
            ->join('units as units_base', 'units_base.id', '=', 'mvtos.unit2_id')
            ->where('products.isinventory', 1)
            ->where('mvtos.cant2', '<>', 0)
            ->whereNotNull('mvtos.cant2')
            ->orderBy('class.name')->orderBy('products.name')->orderBy('units_base.name')
            ->select([
                'mvtos.id',
                'docs.type',
                'docs.date',
                'menus.text as menu_name',
                'products.code',
                'products.name',
                'products.factor',
                'units.name as unit_name',
                'units_base.name as unit_base_name',
                'class.name as class_name',
                'mvtos.cant',
                'mvtos.valueu',
                'mvtos.valuet',
                'mvtos.cant2',
                'mvtos.valueu2',
                'mvtos.valuet2',
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
            ->addColumn('type')
            ->addColumn('date')
            ->addColumn('date_formatted', fn ($model) => Carbon::parse($model->date)->format('d-m-Y'))
            ->addColumn('menu_name')
            ->addColumn('code')
            ->addColumn('name')
            ->addColumn('factor')
            ->addColumn('unit_name')
            ->addColumn('unit_base_name')
            ->addColumn('class_name')
            ->addColumn('cant')
            ->addColumn('cant_formatted', fn ($model) => number_format($model->cant, 4))
            ->addColumn('valueu')
            ->addColumn('valueu_formatted', fn ($model) => number_format($model->valueu))
            ->addColumn('valuet')
            ->addColumn('valuet_formatted', fn ($model) => number_format($model->valuet))
            ->addColumn('cant2')
            ->addColumn('cant2_formatted', fn ($model) => number_format($model->cant2, 4))
            ->addColumn('valueu2')
            ->addColumn('valueu2_formatted', fn ($model) => number_format($model->valueu2))
            ->addColumn('valuet2')
            ->addColumn('valuet2_formatted', fn ($model) => number_format($model->valuet2));
    }

    public function columns(): array
    {
        return [
            Column::make('Tipo', 'type')
                ->sortable()
                ->searchable(),

            Column::make('Menú', 'menu_name')
                ->sortable()
                ->searchable(),

            Column::make('Fecha', 'date_formatted')
                ->sortable()
                ->searchable(),

            Column::make('Categoría', 'class_name', 'class.name')
                ->sortable()
                ->searchable(),

            Column::make('Código', 'code', 'products.code')
                ->sortable()
                ->searchable(),

            Column::make('Nombre', 'name', 'products.name')
                ->sortable()
                ->searchable(),

            Column::make('Unidad Movimiento', 'unit_name', 'units.name')
                ->sortable()
                ->searchable(),

            Column::make('Cantidad', 'cant')
                ->sortable()
                ->searchable()
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Cantidad', 'cant_formatted')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Valor Unitario', 'valueu')
                ->sortable()
                ->searchable()
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Valor Unitario', 'valueu_formatted')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Valor Total', 'valuet')
                ->sortable()
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Valor Total', 'valuet_formatted')
                ->sortable()
                ->visibleInExport(false),

            Column::make('Unidad Base', 'unit_base_name', 'units_base.name')
                ->sortable()
                ->searchable(),

            Column::make('Cantidad Inv', 'cant2')
                ->sortable()
                ->searchable()
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Cantidad Inv', 'cant2_formatted')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Valor Unitario Inv', 'valueu2')
                ->sortable()
                ->searchable()
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Valor Unitario Inv', 'valueu2_formatted')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Valor Total Inv', 'valuet2')
                ->sortable()
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Valor Total Inv', 'valuet2_formatted')
                ->sortable()
                ->visibleInExport(false),

        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('type', 'docs.type')->operators(['contains']),
            Filter::inputText('menu_name', 'menus.text')->operators(['contains']),
            Filter::datetimepicker('date_formatted', 'docs.date'),
            Filter::inputText('class_name', 'class.name')->operators(['contains']),
            Filter::inputText('code', 'products.code')->operators(['contains']),
            Filter::inputText('name', 'products.name')->operators(['contains']),
            Filter::inputText('unit_name', 'units.name')->operators(['contains']),
            Filter::inputText('unit_base_name', 'units_base.name')->operators(['contains']),
        ];
    }

}
