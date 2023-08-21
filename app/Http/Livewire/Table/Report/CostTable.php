<?php

namespace App\Http\Livewire\Table\Report;

use App\Models\Doc;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridColumns};

final class CostTable extends PowerGridComponent
{
    use ActionButton;
    use WithExport;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('Costos_Ventas_'.Carbon::now()->format('d-m-Y_H-i-s'))
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
            ->where('docs.menu_id', 502)
            ->where('mvtos.concept', 50202)
            ->orderBy('docs.id', 'desc')
            ->groupBy('docs.id', 'docs.type', 'docs.num', 'docs.date', 'docs.state')
            ->select([
                'docs.id',
                'docs.type',
                'docs.num',
                'docs.date',
                'docs.state',
                DB::raw('SUM(mvtos.valuet2) as cost'),
                DB::raw('(SELECT SUM(mvtos_sale.valuet) FROM docs as docs_sale INNER JOIN mvtos as mvtos_sale ON docs_sale.id = mvtos_sale.doc_id WHERE docs_sale.id = docs.doc_id AND docs_sale.menu_id = 503) as sale'),
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
            ->addColumn('num')
            ->addColumn('date_formatted', fn (Doc $model) => Carbon::parse($model->date)->format('d/m/Y'))
            ->addColumn('state')
            ->addColumn('cost')
            ->addColumn('cost_formatted', fn (Doc $model) => number_format($model->cost))
            ->addColumn('sale')
            ->addColumn('sale_formatted', fn (Doc $model) => number_format($model->sale));
    }

    public function columns(): array
    {
        return [
            Column::make('Código', 'type')
                ->sortable()
                ->searchable(),

            Column::make('# Producción', 'num')
                ->sortable()
                ->searchable(),

            Column::make('Fecha Producción', 'date_formatted', 'date')
                ->sortable()
                ->searchable(),

            Column::make('Costos de Producción', 'cost')
                ->sortable()
                ->searchable()
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Costos de Producción', 'cost_formatted')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Valor venta', 'sale')
                ->sortable()
                ->searchable()
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Valor venta', 'sale_formatted')
                ->sortable()
                ->searchable()
                ->visibleInExport(false),

            Column::make('Estado', 'state')
                ->sortable()
                ->searchable()
                ->toggleable(false, 'Completado', 'Pendiente'),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('type')->operators(['contains']),
            Filter::inputText('code')->operators(['contains']),
            Filter::inputText('num', 'docs.num')->operators(['contains']),
            Filter::datetimepicker('date_formatted', 'docs.date'),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Actions Method
    |--------------------------------------------------------------------------
    | Enable the method below only if the Routes below are defined in your app.
    |
    */

    /**
     * PowerGrid Doc Action Buttons.
     *
     * @return array<int, Button>
     */

    /*
    public function actions(): array
    {
       return [
           Button::make('edit', 'Edit')
               ->class('bg-indigo-500 cursor-pointer text-white px-3 py-2.5 m-1 rounded text-sm')
               ->route('doc.edit', function(\App\Models\Doc $model) {
                    return $model->id;
               }),

           Button::make('destroy', 'Delete')
               ->class('bg-red-500 cursor-pointer text-white px-3 py-2 m-1 rounded text-sm')
               ->route('doc.destroy', function(\App\Models\Doc $model) {
                    return $model->id;
               })
               ->method('delete')
        ];
    }
    */

    /*
    |--------------------------------------------------------------------------
    | Actions Rules
    |--------------------------------------------------------------------------
    | Enable the method below to configure Rules for your Table and Action Buttons.
    |
    */

    /**
     * PowerGrid Doc Action Rules.
     *
     * @return array<int, RuleActions>
     */

    /*
    public function actionRules(): array
    {
       return [

           //Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($doc) => $doc->id === 1)
                ->hide(),
        ];
    }
    */
}
