<?php

namespace App\Http\Livewire\Table\Report;

use App\Models\Doc;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridColumns};

final class OrderTable extends PowerGridComponent
{
    use ActionButton;
    use WithExport;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('Pedidos_'.Carbon::now()->format('d-m-Y_H-i-s'))
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
            ->join('pets', 'pets.id', '=', 'docs.pet_id')
            ->join('products', 'products.id', '=', 'mvtos.product_id')
            ->leftJoin('items as class', 'class.id', '=', 'products.class')
            ->join('units', 'units.id', '=', 'mvtos.unit_id')
            ->where('docs.menu_id', 501)
            ->where('docs.state', '>=', 0)
            ->orderBy('docs.id', 'desc')
            ->select([
                'docs.*',
                'person.name as person_name',
                'pets.name as pet_name',
                'class.name as class_name',
                'products.name as product_name',
                'units.name as unit_name',
                'mvtos.cant as cant_mvto',
                'mvtos.cant_src as cant_src_mvto',
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
        ->addColumn('state')
        ->addColumn('text')
        ->addColumn('person_name')
        ->addColumn('pet_name')
        ->addColumn('class_name')
        ->addColumn('product_name')
        ->addColumn('unit_name')
        ->addColumn('cant_mvto')
        ->addColumn('cant_mvto_formatted', fn ($model) => number_format($model->cant_mvto, 4))
        ->addColumn('cant_src_mvto')
        ->addColumn('cant_src_mvto_formatted', fn ($model) => number_format($model->cant_src_mvto, 4));
    }

    public function columns(): array
    {
        return [
            Column::make('Tipo', 'code', 'docs.code')
                ->sortable()
                ->searchable(),

            Column::make('# Pedido', 'num', 'docs.num')
                ->sortable()
                ->searchable(),

            Column::make('Fecha', 'date_formatted', 'docs.date')
                ->sortable(),

            Column::make('Cliente', 'person_name', 'person.name')
                ->sortable()
                ->searchable(),

            Column::make('Mascota', 'pet_name', 'pets.name')
                ->sortable()
                ->searchable(),

            Column::make('Clase', 'class_name', 'class.name')
                ->sortable()
                ->searchable(),

            Column::make('Producto', 'product_name', 'products.name')
                ->sortable()
                ->searchable(),

            Column::make('Porciones', 'cant_src_mvto_formatted', 'mvtos.cant_src')
                ->sortable()
                ->visibleInExport(false),

            Column::make('Porciones', 'cant_src_mvto', 'mvtos.cant_src')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Unid. Porción', 'unit_name', 'units.name')
                ->sortable()
                ->searchable(),

            Column::make('Cant. Porción', 'cant_mvto_formatted', 'mvtos.cant')
                ->sortable()
                ->visibleInExport(false),

            Column::make('Cant. Porción', 'cant_mvto', 'mvtos.cant')
                ->visibleInExport(true)
                ->hidden(),            

            Column::make('Estado', 'state', 'docs.state')
                ->sortable()
                ->searchable()
                ->toggleable(false, 'Producción', 'Pendiente'),

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
            Filter::inputText('pet_name', 'pets.name')->operators(['contains']),
            Filter::inputText('class_name', 'class.name')->operators(['contains']),
            Filter::inputText('product_name', 'products.name')->operators(['contains']),
            Filter::inputText('unit_name', 'units.name')->operators(['contains']),
            Filter::datetimepicker('date'),
        ];
    }
}
