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

final class BalanceTable extends PowerGridComponent
{
    use ActionButton;
    use WithExport;

    public string $sortField = 'year';

    public function setUp(): array
    {

        return [
            Exportable::make('Balance_Mes_'.Carbon::now()->format('d-m-Y_H-i-s'))
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
            ->groupBy(DB::raw('month, year'))
            ->orderBy('year')
            ->orderBy('month')
            ->selectRaw("
                YEAR(docs.date) AS year,
                MONTH(docs.date) AS month,
                SUM(CASE WHEN (docs.menu_id = 502 AND mvtos.concept=50202) OR (docs.menu_id = 604) THEN mvtos.valuet2 ELSE 0 END) AS cost,
                SUM(CASE WHEN docs.menu_id = 603 THEN mvtos.valuet ELSE 0 END) AS bill,
                SUM(CASE WHEN docs.menu_id = 503 THEN mvtos.valuet ELSE 0 END) AS sale,
                (SELECT SUM(mvtos_inv.valuet2) 
                    FROM docs as docs_inv
                        INNER JOIN mvtos as mvtos_inv ON docs_inv.id = mvtos_inv.doc_id
                    WHERE YEAR(docs_inv.date) <= year AND MONTH(docs_inv.date) <= month
                        AND mvtos_inv.cant2 <> 0) AS inv
            ");
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()
            ->addColumn('year')
            ->addColumn('month')
            ->addColumn('cost')
            ->addColumn('bill')
            ->addColumn('sale')
            ->addColumn('inv')
            ->addColumn('cost_formatted', fn (Doc $model) => number_format($model->cost))
            ->addColumn('bill_formatted', fn (Doc $model) => number_format($model->bill*-1))
            ->addColumn('sale_formatted', fn (Doc $model) => number_format($model->sale))
            ->addColumn('inv_formatted', fn (Doc $model) => number_format($model->inv));
    }

    public function columns(): array
    {
        return [
            Column::make('Año', 'year')
                ->sortable()
                ->searchable(),

            Column::make('Mes', 'month')
                ->sortable()
                ->searchable(),

            Column::make('Costo', 'cost_formatted')
                ->sortable()
                ->visibleInExport(false),

            Column::make('Gastos', 'bill_formatted')
                ->sortable()
                ->visibleInExport(false),

            Column::make('Ventas', 'sale_formatted')
                ->sortable()
                ->visibleInExport(false),

            Column::make('Inventario', 'inv_formatted')
                ->sortable()
                ->visibleInExport(false),

            Column::make('Costo', 'cost')
                ->sortable()
                ->searchable()
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Gastos', 'bill')
                ->sortable()
                ->searchable()
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Ventas', 'sale')
                ->sortable()
                ->searchable()
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Inventario', 'inv')
                ->sortable()
                ->searchable()
                ->visibleInExport(true)
                ->hidden(),

        ];
    }

    public function filters(): array
    {
        return [];
    }

}
