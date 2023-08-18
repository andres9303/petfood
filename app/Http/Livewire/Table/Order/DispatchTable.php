<?php

namespace App\Http\Livewire\Table\Order;

use App\Models\Doc;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
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
            Exportable::make('Despachos')
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
            ->join('users', 'users.id', '=', 'docs.user_id')
            ->join('docs as produces', 'docs.id', '=', 'produces.doc_id')
            ->join('mvtos', 'produces.id', '=', 'mvtos.doc_id')
            ->where('docs.menu_id', 503)
            ->where('docs.state', '>=', 0)
            ->where('mvtos.concept', 50201)
            ->orderBy('docs.id', 'desc')
            ->groupBy('docs.id', 'docs.code', 'docs.num', 'docs.date', 'users.name', 'produces.id', 'docs.text', 'produces.num', 'produces.date')
            ->select([
                'docs.id',
                'docs.code',
                'docs.num',
                'docs.date',
                'docs.text',
                'produces.id as produce_id',
                'produces.num as produce_num',
                'produces.date as produce_date',
                'users.name as user_name',
                DB::raw("SUM(mvtos.cant_src) as mvtos_cant"),
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
            ->addColumn('date_formatted', fn (Doc $model) => Carbon::parse($model->date)->format('d/m/Y'))
            ->addColumn('text')
            ->addColumn('produce_num')
            ->addColumn('produce_date_formatted', fn (Doc $model) => Carbon::parse($model->produce_date)->format('d/m/Y'))
            ->addColumn('user_name')
            ->addColumn('mvtos_cant')
            ->addColumn('mvtos_cant_formatted', fn ($model) => number_format($model->mvtos_cant, 4));
    }

    public function columns(): array
    {
        return [
            Column::make('Tipo', 'code', 'docs.code')
                ->sortable()
                ->searchable(),

            Column::make('# Documento', 'num', 'docs.num')
                ->sortable()
                ->searchable(),

            Column::make('Fecha', 'date_formatted', 'docs.date')
                ->sortable(),

            Column::make('# Preparación', 'produce_num', 'produces.num')
                ->sortable()
                ->searchable(),

            Column::make('Fecha Preparación', 'produce_date_formatted', 'produces.date')
                ->sortable(),

            Column::make('Usuario', 'user_name', 'users.name')
                ->sortable()
                ->searchable(),

            Column::make('Porciones', 'mvtos_cant_formatted')
                ->sortable()
                ->visibleInExport(false),

            Column::make('Porciones', 'mvtos_cant')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Observación', 'text', 'docs.text')
                ->sortable()
                ->searchable(),

        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('code', 'docs.code')->operators(['contains']),
            Filter::inputText('num', 'docs.num')->operators(['contains']),
            Filter::inputText('text', 'docs.text')->operators(['contains']),
            Filter::inputText('user_name', 'users.name')->operators(['contains']),
            Filter::datetimepicker('date'),
            Filter::datetimepicker('produce_date'),
        ];
    }

    public function actions(): array
    {
       return [
            Button::make('edit')
                ->caption('<i class="fa fa-edit mr-1"></i> Editar')
                ->class('inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500')
                ->target('_self')
                ->route('dispatch.edit', function($dispatch) {
                    return ['produce' => $dispatch->produce_id, 'dispatch' => $dispatch->id];
                }),

            Button::make('destroy')
                ->caption('<i class="fa fa-trash-alt mr-1"></i> Anular')
                ->class('inline-flex items-center justify-center mt-2 px-4 py-2 text-sm font-medium text-white bg-red-500 border border-transparent rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500')
                ->route('dispatch.destroy', function($dispatch) {
                    return ['produce' => $dispatch->produce_id, 'dispatch' => $dispatch->id];
                })
                ->method('delete')
                ->target('_self')
        ];
    }
}
