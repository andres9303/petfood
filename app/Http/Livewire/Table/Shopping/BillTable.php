<?php

namespace App\Http\Livewire\Table\Shopping;

use App\Models\Doc;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridColumns};

final class BillTable extends PowerGridComponent
{
    use ActionButton;
    use WithExport;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('Gastos')
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
        return Doc::query()
            ->join('mvtos', 'mvtos.doc_id', '=', 'docs.id')
            ->join('people as person', 'person.id', '=', 'docs.person_id')
            ->join('products', 'products.id', '=', 'mvtos.product_id')
            ->join('units', 'units.id', '=', 'mvtos.unit_id')
            ->where('docs.menu_id', 603)
            ->where('docs.state', '>=', 0)
            ->orderBy('docs.id', 'desc')
            ->select([
                'docs.*',
                'person.name as person_name',
                'products.name as product_name',
                'units.name as unit_name',
                'mvtos.cant as cant_mvto',
                'mvtos.valueu as valueu_mvto',
                'mvtos.iva as iva_mvto',
                'mvtos.valuet as valuet_mvto',
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
        ->addColumn('num')
        ->addColumn('date_formatted', fn ($model) => Carbon::parse($model->date)->format('d/m/Y'))
        ->addColumn('text')
        ->addColumn('person_name')
        ->addColumn('product_name')
        ->addColumn('unit_name')
        ->addColumn('cant_mvto')
        ->addColumn('cant_mvto_formatted', fn ($model) => number_format($model->cant_mvto, 4))
        ->addColumn('valueu_mvto')
        ->addColumn('valueu_mvto_formatted', fn ($model) => number_format($model->valueu_mvto))
        ->addColumn('iva_mvto')
        ->addColumn('iva_mvto_formatted', fn ($model) => number_format($model->iva_mvto))
        ->addColumn('valuet_mvto')
        ->addColumn('valuet_mvto_formatted', fn ($model) => number_format($model->valuet_mvto));
    }

    public function columns(): array
    {
        return [
            Column::make('Código', 'code', 'docs.code')
                ->sortable()
                ->searchable(),

            Column::make('# Gasto', 'num', 'docs.num')
                ->sortable()
                ->searchable(),

            Column::make('Fecha', 'date_formatted', 'docs.date')
                ->sortable(),

            Column::make('Proveedor', 'person_name', 'person.name')
                ->sortable()
                ->searchable(),

            Column::make('Producto', 'product_name', 'products.name')
                ->sortable()
                ->searchable(),

            Column::make('Unidad', 'unit_name', 'units.name')
                ->sortable()
                ->searchable(),

            Column::make('Cantidad', 'cant_mvto_formatted', 'mvtos.cant')
                ->sortable()
                ->visibleInExport(false),

            Column::make('Cantidad', 'cant_mvto', 'mvtos.cant')
                ->visibleInExport(true)
                ->hidden(),            

            Column::make('Precio Unitario', 'valueu_mvto_formatted', 'mvtos.valueu')
                ->sortable()
                ->visibleInExport(false),

            Column::make('Precio Unitario', 'valueu_mvto', 'mvtos.valueu')
                ->visibleInExport(true)
                ->hidden(),            

            Column::make('IVA', 'iva_mvto_formatted', 'mvtos.iva')
                ->sortable()
                ->visibleInExport(false),

            Column::make('IVA', 'iva_mvto', 'mvtos.iva')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Valor Total', 'valuet_mvto_formatted', 'mvtos.valuet')
                ->sortable()
                ->visibleInExport(false),

            Column::make('Valor Total', 'valuet_mvto', 'mvtos.valuet')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Detalle', 'text', 'docs.text')
                ->sortable()
                ->searchable(),

        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('code', 'docs.code')->operators(['contains']),
            Filter::inputText('num', 'docs.num')->operators(['contains']),
            Filter::inputText('person_name', 'person.name')->operators(['contains']),
            Filter::inputText('product_name', 'products.name')->operators(['contains']),
            Filter::inputText('unit_name', 'units.name')->operators(['contains']),
            Filter::datetimepicker('date'),
        ];
    }

    public function actions(): array
    {
       return [
            Button::make('edit')
                ->caption('<i class="fa fa-edit mr-1"></i> Editar')
                ->class('inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500')
                ->target('_self')
                ->route('bill.edit', function($bill) {
                    return ['bill' => $bill->id];
                }),

            Button::make('destroy')
                ->caption('<i class="fa fa-trash-alt mr-1"></i> Eliminar')
                ->class('inline-flex items-center justify-center mt-2 px-4 py-2 text-sm font-medium text-white bg-red-500 border border-transparent rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500')
                ->route('bill.destroy', function($bill) {
                    return ['bill' => $bill->id];
                })
                ->method('delete')
                ->target('_self')
        ];
    }
}
