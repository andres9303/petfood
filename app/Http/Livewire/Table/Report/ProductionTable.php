<?php

namespace App\Http\Livewire\Table\Report;

use App\Models\Mvto;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridColumns};

final class ProductionTable extends PowerGridComponent
{
    use ActionButton;
    use WithExport;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('Producción_Dietas_'.Carbon::now()->format('d-m-Y_H-i-s'))
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
            ->where('docs.menu_id', 502)
            ->where('mvtos.concept', 50201)
            ->where('docs.state', '>=', 0)
            ->orderBy('docs.id', 'desc')
            ->select([
                'mvtos.id',
                'docs.code',
                'docs.num',
                'docs.date',
                'docs.state',
                'products.code as product_code',
                'products.name as product_name',
                'units.name as unit_name',
                'class.name as class_name',
                'mvtos.cant',
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
            ->addColumn('num')
            ->addColumn('state')
            ->addColumn('date_formatted', fn ($model) => Carbon::parse($model->date)->format('d-m-Y'))
            ->addColumn('product_code')
            ->addColumn('product_name')
            ->addColumn('unit_name')
            ->addColumn('unit_base_name')
            ->addColumn('class_name')
            ->addColumn('cant')
            ->addColumn('cant_formatted', fn ($model) => number_format($model->cant, 4));
    }

    public function columns(): array
    {
        return [
            Column::make('Código', 'code', 'docs.code')
                ->sortable()
                ->searchable(),

            Column::make('# Documento', 'num', 'docs.num')
                ->sortable()
                ->searchable(),

            Column::make('Fecha', 'date_formatted', 'docs.date')
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

            Column::make('Cantidad', 'cant')
                ->sortable()
                ->searchable()
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Cantidad', 'cant_formatted')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Estado', 'state', 'docs.state')
                ->sortable()
                ->searchable()
                ->toggleable(false, 'Completado', 'Pendiente'),

        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('code', 'docs.code')->operators(['contains']),
            Filter::inputText('num', 'docs.num')->operators(['contains']),
            Filter::datetimepicker('date_formatted', 'docs.date'),
            Filter::inputText('class_name', 'class.name')->operators(['contains']),
            Filter::inputText('product_code', 'products.code')->operators(['contains']),
            Filter::inputText('product_name', 'products.name')->operators(['contains']),
            Filter::inputText('unit_name', 'units.name')->operators(['contains']),
        ];
    }

}
