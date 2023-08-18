<?php

namespace App\Http\Livewire\Table\Pet;

use App\Models\Race;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridColumns};

final class RaceTable extends PowerGridComponent
{
    use ActionButton;
    use WithExport;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('Razas')
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
        return Race::query()
            ->join('animals', 'animals.id', '=', 'races.animal_id')
            ->select('races.*', 
                'animals.name as name_animal', 
            );
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()
            ->addColumn('name')
            ->addColumn('name_animal')
            ->addColumn('state');
    }

    public function columns(): array
    {
        return [
            Column::make('Nombre Raza', 'name_animal', 'animals.name')
                ->sortable()
                ->searchable(),
                
            Column::make('Nombre Raza', 'name', 'races.name')
                ->sortable()
                ->searchable(),

            Column::make('Estado', 'state')
                ->sortable()
                ->searchable()
                ->toggleable(false, 'Activo', 'Inactivo'),

        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name_animal', 'animals.name')->operators(['contains']),
            Filter::inputText('name', 'races.name')->operators(['contains']),
        ];
    }

    public function actions(): array
    {
       return [
            Button::make('edit')
                ->caption('<i class="fa fa-edit mr-1"></i> Editar')
                ->class('inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500')
                ->target('_self')
                ->route('race.edit', function(Race $race) {
                    return ['race' => $race->id];
                }),

            Button::make('destroy')
                ->caption('<i class="fa fa-trash-alt mr-1"></i> Eliminar')
                ->class('inline-flex items-center justify-center mt-2 px-4 py-2 text-sm font-medium text-white bg-red-500 border border-transparent rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500')
                ->route('race.destroy', function(Race $race) {
                    return ['race' => $race->id];
                })
                ->method('delete')
                ->target('_self')
        ];
    }

    public function actionRules(): array
    {
       return [
            Rule::button('destroy')
                ->when(fn($race) => $race->pets()->count() > 0)
                ->hide(),
        ];
    }
}
