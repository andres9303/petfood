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

final class ProduceTable extends PowerGridComponent
{
    use ActionButton;
    use WithExport;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('Preparaciones')
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
            ->join('users', 'users.id', '=', 'docs.user_id')
            ->where('docs.menu_id', 502)
            ->where('docs.state', '>=', 0)
            ->orderBy('docs.id', 'desc')
            ->groupBy('docs.id', 'docs.code', 'docs.num', 'docs.date', 'users.name', 'docs.text', 'docs.state')
            ->select([
                'docs.id',
                'docs.code',
                'docs.num',
                'docs.date',
                'docs.text',
                'docs.state',
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
            ->addColumn('state')
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

            Column::make('Usuario', 'user_name', 'users.name')
                ->sortable()
                ->searchable(),

            Column::make('Porciones', 'mvtos_cant_formatted')
                ->sortable()
                ->visibleInExport(false),

            Column::make('Porciones', 'mvtos_cant')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Estado', 'state', 'docs.state')
                ->sortable()
                ->searchable()
                ->toggleable(false, 'Completado', 'Pendiente'),

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
        ];
    }
    
    public function actions(): array
    {
       return [
            Button::make('edit')
                ->caption('<i class="fa fa-edit mr-1"></i> Planificar')
                ->class('inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500')
                ->target('_self')
                ->route('produce.edit', function($produce) {
                    return ['produce' => $produce->id];
                }),

            Button::add('complete')
                ->caption('<i class="fas fa-spell-check mr-2"></i> Completar')
                ->class('inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-yellow-500 border border-transparent rounded-md hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500')
                ->target('_self')
                ->tooltip('Completar')
                ->route('produce.edit.complete', function($produce) {
                    return ['produce' => $produce->id];
                }),
                
            Button::add('show')
                ->caption('<i class="fas fa-eye mr-1"></i> Ver detalle')
                ->tooltip('Ver detalle')
                ->class('inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-green-500 border border-transparent rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500')
                ->route('produce.show', function($produce) {
                    return ['produce' => $produce->id];
                })
                ->target('_self'),

            Button::add('dispatch')
                ->caption('<i class="fas fa-paper-plane mr-1"></i> Despachar')
                ->tooltip('Despachar')
                ->class('inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-alternative-500 border border-transparent rounded-md hover:bg-alternative-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-alternative-500')
                ->route('dispatch.create', function($produce) {
                    return ['produce' => $produce->id];
                })
                ->target('_self'),
                
            Button::make('destroy')
                ->caption('<i class="fa fa-trash-alt mr-1"></i> Eliminar')
                ->class('inline-flex items-center justify-center mt-2 px-4 py-2 text-sm font-medium text-white bg-red-500 border border-transparent rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500')
                ->route('produce.destroy', function($produce) {
                    return ['produce' => $produce->id];
                })
                ->method('delete')
                ->target('_self')
        ];
    }
    
    public function actionRules(): array
    {
       return [
            Rule::button('edit')
                ->when(fn($doc) => $doc->state > 0)
                ->hide(),

            Rule::button('complete')
                ->when(fn($doc) => $doc->state > 1)
                ->hide(),

            Rule::button('dispatch')
                ->when(fn($doc) => $doc->state != 1)
                ->hide(),

            Rule::button('destroy')
                ->when(fn($doc) => $doc->state > 0)
                ->hide(),
        ];
    }
}
