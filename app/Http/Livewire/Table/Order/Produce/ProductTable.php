<?php

namespace App\Http\Livewire\Table\Order\Produce;

use App\Models\Doc;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridColumns};

final class ProductTable extends PowerGridComponent
{
    use ActionButton;
    use WithExport;

    public $produce_id;

    public function setUp(): array
    {
        $this->showCheckBox();
        $produce = Doc::find($this->produce_id);

        return [
            Exportable::make('Productos_PRP_'.$produce->num)
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
            ->where('mvtos.concept', 50202)
            ->orderBy('products.name')
            ->select([
                'docs.id',
                'products.name as product_name',
                'units.name as unit_name',
                'mvtos.cant as cant_mvto',
                'products.factor as product_factor',
                DB::raw('(SELECT SUM(invs.cant2) FROM mvtos as invs WHERE invs.product_id = mvtos.product_id AND invs.unit2_id = mvtos.unit_id AND mvtos.id <> invs.id AND cant2 <> 0) as cant_inv')
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
            ->addColumn('cant_prepared_mvto', fn ($model) => $model->cant_mvto / $model->product_factor)
            ->addColumn('cant_prepared_mvto_formatted', fn ($model) => number_format($model->cant_mvto / $model->product_factor, 4))
            ->addColumn('cant_inv')
            ->addColumn('cant_inv_formatted', fn ($model) => number_format($model->cant_inv, 4));
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

            Column::make('Cantidad Preparada', 'cant_mvto_formatted', 'mvtos.cant')
                ->sortable()
                ->visibleInExport(false),

            Column::make('Cantidad Preparada', 'cant_mvto', 'mvtos.cant')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Cantidad Sin preparar', 'cant_prepared_mvto_formatted')
                ->sortable()
                ->visibleInExport(false),

            Column::make('Cantidad Sin preparar', 'cant_prepared_mvto')
                ->visibleInExport(true)
                ->hidden(),

            Column::make('Cantidad en inventario', 'cant_inv_formatted')
                ->sortable()
                ->visibleInExport(false),

            Column::make('Cantidad en inventario', 'cant_inv')
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
