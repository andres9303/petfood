<?php

namespace App\Http\Livewire\Table\Person;

use App\Models\Person;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridColumns};

final class PersonTable extends PowerGridComponent
{
    use ActionButton;
    use WithExport;

    public $person_filter;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('Clientes')
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
        return Person::query()
            ->where($this->person_filter, true);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()
            ->addColumn('code')
            ->addColumn('name')
            ->addColumn('email')
            ->addColumn('phone')
            ->addColumn('address')
            ->addColumn('birth_formatted', fn (Person $model) => Carbon::parse($model->birth)->format('d/m/Y'));
    }

    public function columns(): array
    {
        return [
            Column::make('Identificación', 'code')
                ->sortable()
                ->searchable(),

            Column::make('Nombre', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Email', 'email')
                ->sortable()
                ->searchable(),

            Column::make('Teléfono', 'phone')
                ->sortable()
                ->searchable(),

            Column::make('Dirección', 'address')
                ->sortable()
                ->searchable(),

            Column::make('Fecha Nacimiento', 'birth_formatted', 'birth')
                ->sortable(),


        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('code')->operators(['contains']),
            Filter::inputText('name')->operators(['contains']),
            Filter::inputText('email')->operators(['contains']),
            Filter::inputText('phone')->operators(['contains']),
            Filter::inputText('address')->operators(['contains']),
            Filter::datepicker('birth'),
        ];
    }

    public function actions(): array
    {
        $route = $this->person_filter == 'isclient' ? 'client' : 'supplier';
       return [
            Button::make('edit')
                ->caption('<i class="fa fa-edit mr-1"></i> Editar')
                ->class('inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500')
                ->target('_self')
                ->route($route.'.edit', function(Person $person) use ($route) {
                    return [$route => $person->id];
                }),

            Button::make('destroy')
               ->caption('<i class="fa fa-trash-alt mr-1"></i> Eliminar')
               ->class('inline-flex items-center justify-center mt-2 px-4 py-2 text-sm font-medium text-white bg-red-500 border border-transparent rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500')
               ->route('client.destroy', function(Person $person) {
                   return ['client' => $person->id, 'type' => $this->person_filter];
               })
               ->method('delete')
               ->target('_self')
        ];
    }
}
