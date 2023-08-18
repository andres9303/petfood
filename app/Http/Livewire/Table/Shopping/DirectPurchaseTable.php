<?php

namespace App\Http\Livewire\Table\Shopping;

use App\Models\Doc;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridColumns};

final class DirectPurchaseTable extends PowerGridComponent
{
    use ActionButton;
    use WithExport;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('Compras_Directas')
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
                ->join('people as person', 'person.id', '=', 'docs.person_id')
                ->where('docs.menu_id', 602)
                ->where('docs.state', '>=', 0)
                ->orderBy('docs.id', 'desc')
                ->select([
                    'docs.*',
                    'person.name as person_name',
                ]);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()
            ->addColumn('person_name')
            ->addColumn('code')
            ->addColumn('num')
            ->addColumn('date_formatted', fn (Doc $model) => Carbon::parse($model->date)->format('d/m/Y'))
            ->addColumn('subtotal')
            ->addColumn('iva')
            ->addColumn('total')
            ->addColumn('text')
            ->addColumn('subtotal_formatted', fn (Doc $model) => number_format($model->subtotal))
            ->addColumn('iva_formatted', fn (Doc $model) => number_format($model->iva))
            ->addColumn('total_formatted', fn (Doc $model) => number_format($model->total));
    }

    public function columns(): array
    {
        return [
            Column::make('Código', 'code', 'docs.code')
                ->sortable()
                ->searchable(),

            Column::make('Num. Factura', 'num', 'docs.num')
                ->sortable()
                ->searchable(),

            Column::make('Proveedor', 'person_name', 'person.name')
                ->sortable()
                ->searchable(),
            
            Column::make('Fecha', 'date_formatted', 'docs.date')
                ->sortable(),
                
            Column::make('Subtotal', 'subtotal')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Iva', 'iva')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Total', 'total')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Subtotal', 'subtotal_formatted')
                ->sortable()
                ->visibleInExport(false),

            Column::make('Iva', 'iva_formatted')
                ->sortable()
                ->visibleInExport(false),

            Column::make('Total', 'total_formatted')
                ->sortable()
                ->visibleInExport(false),

            Column::make('Observación', 'text', 'docs.text')
                ->sortable()
                ->searchable(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('num')->operators(['contains']),
            Filter::inputText('person_name')->operators(['contains']),
            Filter::inputText('code')->operators(['contains']),
            Filter::inputText('text')->operators(['contains']),
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
                ->route('direct-purchase.edit', function($direct_purchase) {
                    return ['direct_purchase' => $direct_purchase->id];
                }),
        ];
    }
}
