<?php

namespace App\Http\Livewire\Table\Pet;

use App\Models\Pet;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridColumns};

final class PetTable extends PowerGridComponent
{
    use ActionButton;
    use WithExport;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('Mascotas')
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
        return Pet::query()
            ->join('races', 'races.id', '=', 'pets.race_id')
            ->join('animals', 'animals.id', '=', 'races.animal_id')
            ->join('people', 'people.id', '=', 'pets.person_id')
            ->select('pets.*',
                'people.name as name_person',
                'races.name as name_race',
                'animals.name as name_animal'
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
            ->addColumn('name_person')
            ->addColumn('name_race')
            ->addColumn('name_animal')
            ->addColumn('date_formatted', fn (Pet $model) => Carbon::parse($model->date)->format('d/m/Y'))
            ->addColumn('living')
            ->addColumn('sib')
            ->addColumn('diet')
            ->addColumn('exercise')
            ->addColumn('allergy')
            ->addColumn('vaccine')
            ->addColumn('deworming')
            ->addColumn('health')
            ->addColumn('reproductive')
            ->addColumn('weight')
            ->addColumn('text')
            ->addColumn('state')
            ->addColumn('img', function ($pet) {
                return view('components.column-image', [
                    'image' => $pet->image,
                    'default' => 'mascota',
                ]);
            });;
    }

    public function columns(): array
    {
        return [
            Column::make('', 'img')
                ->visibleInExport(false),

            Column::make('Tipo', 'name_animal', 'animals.name')
                ->sortable()
                ->searchable(),

            Column::make('Raza', 'name_race', 'races.name')
                ->sortable()
                ->searchable(),

            Column::make('Propietario', 'name_person', 'people.name')
                ->sortable()
                ->searchable(),

            Column::make('Nombre', 'name', 'pets.name')
                ->sortable()
                ->searchable(),

            Column::make('Fecha Nacimiento', 'date_formatted', 'pet.date')
                ->sortable(),

            Column::make('Vive en casa o en apartamento', 'living')
                ->sortable()
                ->searchable(),

            Column::make('Convive con otras mascotas', 'sib')
                ->toggleable(false, 'Si', 'No'),

            Column::make('Dieta actual', 'diet')
                ->sortable()
                ->searchable(),

            Column::make('Nivel de actividad', 'exercise')
                ->sortable()
                ->searchable(),

            Column::make('Alergias', 'allergy')
                ->sortable()
                ->searchable(),

            Column::make('Vacunación', 'vaccine')
                ->sortable()
                ->searchable(),

            Column::make('Desparasitación', 'deworming')
                ->sortable()
                ->searchable(),

            Column::make('Problemas previos de salud', 'health')
                ->sortable()
                ->searchable(),

            Column::make('Estado reproductivo', 'reproductive')
                ->sortable()
                ->searchable(),

            Column::make('Peso', 'weight')
                ->sortable()
                ->searchable(),

            Column::make('Observaciones', 'text')
                ->sortable()
                ->searchable(),

            Column::make('Estado', 'state')
                ->toggleable(false, 'Activo', 'Inactivo'),

        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name_animal', 'animals.name')->operators(['contains']),
            Filter::inputText('name_race', 'races.name')->operators(['contains']),
            Filter::inputText('name_person', 'people.name')->operators(['contains']),
            Filter::inputText('name', 'pets.name')->operators(['contains']),
            Filter::datepicker('date_formatted', 'pets.date'),
            Filter::inputText('living', 'pets.')->operators(['contains']),
            Filter::boolean('sib', 'pets.sib'),
            Filter::boolean('state', 'pets.state'),
            Filter::inputText('diet', 'pets.diet')->operators(['contains']),
            Filter::inputText('exercise', 'pets.exercise')->operators(['contains']),
            Filter::inputText('allergy', 'pets.allergy')->operators(['contains']),
            Filter::inputText('vaccine', 'pets.vaccine')->operators(['contains']),
            Filter::inputText('deworming', 'pets.deworming')->operators(['contains']),
            Filter::inputText('health', 'pets.health')->operators(['contains']),
            Filter::inputText('reproductive', 'pets.reproductive')->operators(['contains']),
            Filter::inputText('text', 'pets.text')->operators(['contains']),
        ];
    }

    public function actions(): array
    {
       return [
            Button::make('edit')
                ->caption('<i class="fa fa-edit mr-1"></i> Editar')
                ->class('inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500')
                ->target('_self')
                ->route('pet.edit', function(Pet $pet) {
                    return ['pet' => $pet->id];
                }),

            Button::add('image')
                ->caption('<i class="fas fa-camera-retro mr-2"></i> Imagen')
                ->class('inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-yellow-500 border border-transparent rounded-md hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500')
                ->target('_self')
                ->tooltip('Cambiar Imagen')
                ->route('pet.edit.image', function(Pet $pet) {
                    return ['pet' => $pet->id];
                }),
                
            Button::add('show')
                ->caption('<i class="fas fa-eye mr-1"></i> Ver historia')
                ->tooltip('Ver detalle')
                ->class('inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-green-500 border border-transparent rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500')
                ->route('pet.show', function(Pet $pet) {
                    return ['pet' => $pet->id];
                })
                ->target('_self'),
        ];
    }
}
