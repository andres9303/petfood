<?php

namespace App\Http\Livewire\Table\Master;

use App\Models\Unit;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridColumns};

final class UnitTable extends PowerGridComponent
{
    use ActionButton;
    use WithExport;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('Unidades')
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
        return Unit::query()
            ->leftJoin('units as base', 'base.id', '=', 'units.unit_id')
            ->select([
                'units.*',
                'base.name as base_name',
            ]);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()
            ->addColumn('name')
            ->addColumn('base_name')
            ->addColumn('factor')
            ->addColumn('state');
    }

    public function columns(): array
    {
        return [
            Column::make('Nombre Unidad', 'name', 'units.name')
                ->sortable()
                ->searchable(),

            Column::make('Unidad Base', 'base_name', 'base.name')
                ->sortable()
                ->searchable(),

            Column::make('Factor', 'factor', 'units.factor')
                ->sortable()
                ->searchable(),

            Column::make('Estado', 'state', 'units.state')
                ->sortable()
                ->searchable()
                ->toggleable(false, 'Activo', 'Inactivo'),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name', 'units.name')->operators(['contains']),
            Filter::inputText('base_name', 'base.name')->operators(['contains']),
            Filter::inputText('factor', 'units.factor')->operators(['contains']),
            Filter::inputText('state', 'units.state')->operators(['contains']),
        ];
    }

    public function actions(): array
    {
       return [
            Button::make('edit')
                ->caption('<i class="fa fa-edit mr-1"></i> Editar')
                ->class('inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500')
                ->target('_self')
                ->route('unit.edit', function(Unit $unit) {
                    return ['unit' => $unit->id];
                }),
        ];
    }
    
    public function actionRules(): array
    {
       return [
            Rule::button('edit')
                ->when(fn($unit) => $unit->id === $unit->unit_id)
                ->hide(),
        ];
    }
}
