<?php

namespace App\Http\Livewire\Table\Report;

use App\Models\Mvto;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridColumns};

final class DispatchTable extends PowerGridComponent
{
    use ActionButton;
    use WithExport;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('Despacho_Pedidos_'.Carbon::now()->format('d-m-Y_H-i-s'))
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
            ->join('products', 'mvtos.product_id', '=', 'products.id')
            ->leftJoin('items as class', 'class.id', '=', 'products.class')
            ->join('units', 'units.id', '=', 'mvtos.unit_id')
            ->join('mvtos as mvtos_base', 'mvtos_base.id', '=', 'mvtos.mvto_id')
            ->join('docs as docs_base', 'docs_base.id', '=', 'mvtos_base.doc_id')
            ->join('people', 'people.id', '=', 'docs_base.person_id')
            ->join('pets', 'pets.id', '=', 'docs_base.pet_id')
            ->where('docs.menu_id', 503)
            ->where('docs.state', '>=', 0)
            ->orderBy('docs.id', 'desc')
            ->select([
                'mvtos.id',
                'docs_base.num as base_num',
                'docs.num',
                'docs_base.date as base_date',
                'docs.date',
                'people.name as person_name',
                'pets.name as pet_name',
                'products.code as product_code',
                'products.name as product_name',
                'units.name as unit_name',
                'class.name as class_name',
                'mvtos_base.cant as base_cant',
                'mvtos.cant',
                'mvtos_base.cant_src as base_cant_src',
                'mvtos.cant_src',
                'mvtos_base.valueu as base_valueu',
                'mvtos.valueu',
                'mvtos_base.valuet as base_valuet',
                'mvtos.valuet',
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
            ->addColumn('base_num')
            ->addColumn('num')
            ->addColumn('person_name')
            ->addColumn('pet_name')
            ->addColumn('base_date_formatted', fn ($model) => Carbon::parse($model->base_date)->format('d-m-Y'))
            ->addColumn('date_formatted', fn ($model) => Carbon::parse($model->date)->format('d-m-Y'))
            ->addColumn('product_code')
            ->addColumn('product_name')
            ->addColumn('unit_name')
            ->addColumn('unit_base_name')
            ->addColumn('class_name')
            ->addColumn('base_cant')
            ->addColumn('base_cant_formatted', fn ($model) => number_format($model->base_cant, 4))
            ->addColumn('base_cant_total', fn ($model) => $model->base_cant * $model->base_cant_src)
            ->addColumn('base_cant_total_formatted', fn ($model) => number_format($model->base_cant * $model->base_cant_src))
            ->addColumn('base_cant_src')
            ->addColumn('base_cant_src_formatted', fn ($model) => number_format($model->base_cant_src, 2))
            ->addColumn('cant')
            ->addColumn('cant_formatted', fn ($model) => number_format($model->cant, 4))
            ->addColumn('cant_unt', fn ($model) => $model->cant_src > 0 ? $model->cant / $model->cant_src : 0)
            ->addColumn('cant_unt_formatted', fn ($model) => number_format($model->cant_src > 0 ? $model->cant / $model->cant_src : 0, 4))
            ->addColumn('cant_src')
            ->addColumn('cant_src_formatted', fn ($model) => number_format($model->cant_src, 2))
            ->addColumn('valueu')
            ->addColumn('valueu_formatted', fn ($model) => number_format($model->valueu, 2))
            ->addColumn('valuet')
            ->addColumn('valuet_formatted', fn ($model) => number_format($model->valuet));
    }

    public function columns(): array
    {
        return [
            Column::make('# Pedido', 'base_num', 'docs_base.num')
                ->sortable()
                ->searchable(),

            Column::make('Fecha Pedido', 'base_date_formatted', 'base_docs.date')
                ->sortable()
                ->searchable(),

            Column::make('# Despacho', 'num', 'docs.num')
                ->sortable()
                ->searchable(),

            Column::make('Fecha Despacho', 'date_formatted', 'docs.date')
                ->sortable()
                ->searchable(),

            Column::make('Cliente', 'person_name', 'people.name')
                ->sortable()
                ->searchable(),

            Column::make('Mascota', 'pet_name', 'pets.name')
                ->sortable()
                ->searchable(),

            Column::make('Categoría', 'class_name', 'class.name')
                ->sortable()
                ->searchable(),

            Column::make('Código Producto', 'product_code', 'products.code')
                ->sortable()
                ->searchable(),

            Column::make('Producto ', 'product_name', 'products.name')
                ->sortable()
                ->searchable(),

            Column::make('Unidad', 'unit_name', 'units.name')
                ->sortable()
                ->searchable(),

            Column::make('Cant. Porciones Pedido', 'base_cant_src')
                ->sortable()
                ->searchable()
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Cant. Porciones Pedido', 'base_cant_src_formatted')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Cant. Porciones Despacho', 'cant_src')
                ->sortable()
                ->searchable()
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Cant. Porciones Despacho', 'cant_src_formatted')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Cant. por porción Pedido', 'base_cant')
                ->sortable()
                ->searchable()
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Cant. por porción Pedido', 'base_cant_formatted')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Cant. por porción Despacho', 'cant_unt')
                ->sortable()
                ->searchable()
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Cant. por porción Despacho', 'cant_unt_formatted')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Cant. Total Pedido', 'base_cant_total')
                ->sortable()
                ->searchable()
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Cant. Total Pedido', 'base_cant_total_formatted')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Cant. Total Despacho', 'cant')
                ->sortable()
                ->searchable()
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Cant. Total Despacho', 'cant_formatted')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Precio unitario', 'valueu')
                ->sortable()
                ->searchable()
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Precio unitario', 'valueu_formatted')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Precio Total', 'valuet')
                ->sortable()
                ->searchable()
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Precio Total', 'valuet_formatted')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('base_num', 'docs_base.num')->operators(['contains']),
            Filter::inputText('num', 'docs.num')->operators(['contains']),
            Filter::datetimepicker('date_formatted', 'docs.date'),
            Filter::datetimepicker('base_date_formatted', 'docs_base.date'),
            Filter::inputText('class_name', 'class.name')->operators(['contains']),
            Filter::inputText('product_code', 'products.code')->operators(['contains']),
            Filter::inputText('product_name', 'products.name')->operators(['contains']),
            Filter::inputText('person_name', 'people.name')->operators(['contains']),
            Filter::inputText('pet_name', 'pets.name')->operators(['contains']),
            Filter::inputText('unit_name', 'units.name')->operators(['contains']),
        ];
    }

}
