<?php

namespace App\Http\Livewire\Table\Order\Produce;

use App\Models\Doc;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridColumns};

final class DietTable extends PowerGridComponent
{
    use ActionButton;
    use WithExport;

    public $produce_id;
    
    public function setUp(): array
    {
        $this->showCheckBox();
        $produce = Doc::find($this->produce_id);

        return [
            Exportable::make('Dietas_PRP_'.$produce->num)
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
            ->join('products', 'products.id', '=', 'mvtos.product_id')
            ->join('units', 'units.id', '=', 'mvtos.unit_id')
            ->where('docs.id', $this->produce_id)
            ->where('mvtos.concept', 50201)
            ->orderBy('products.name')
            ->select([
                'docs.*',
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
            Column::make('Producto', 'product_name', 'products.name')
                ->sortable()
                ->searchable(),

            Column::make('Unidad', 'unit_name', 'units.name')
                ->sortable()
                ->searchable(),

            Column::make('Cantidad Total', 'cant_mvto_formatted', 'mvtos.cant')
                ->sortable()
                ->visibleInExport(false),

            Column::make('Cantidad Total', 'cant_mvto', 'mvtos.cant')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('# Porciones', 'cant_src_mvto_formatted', 'mvtos.cant_src')
                ->sortable()
                ->visibleInExport(false),

            Column::make('# Porciones', 'cant_src_mvto', 'mvtos.cant_src')
                ->visibleInExport(true)
                ->hidden(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('product_name', 'products.name')->operators(['contains']),
            Filter::inputText('unit_name', 'units.name')->operators(['contains']),
        ];
    }
}
