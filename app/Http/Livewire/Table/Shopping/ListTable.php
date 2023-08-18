<?php

namespace App\Http\Livewire\Table\Shopping;

use App\Models\Doc;
use App\Models\Mvto;
use App\Models\Product;
use App\Services\UnitConversionService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridColumns;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;

final class ListTable extends PowerGridComponent
{
    use ActionButton;

    public $orders;
    protected $unitConversionService;

    public function setUp(): array
    {
        $this->unitConversionService = app(UnitConversionService::class);

        return [
            Header::make(),
            Footer::make()
                ->showPerPage(50)
                ->showRecordCount(),
        ];
    }

    public function datasource(): ?Collection
    {        
        $products = collect([]);

        if ($this->orders == '')
            return $products;
            
        foreach (explode(',', $this->orders) as $order_id)
        {
            $order = Doc::find(intval($order_id));
            $cant_diet = $this->unitConversionService->convert($order->mvtos[0]->cant*$order->mvtos[0]->cant_src, $order->mvtos[0]->unit_id, $order->mvtos[0]->product->unit_id);
            $ingredients = $order->mvtos[0]->product->recipes;
            foreach ($ingredients as $recipe)
            {
                $product = Product::find($recipe->ref_id);
                $p = $products->where('id', $recipe->ref_id)->first();                
                if ($p == null)
                {                    
                    $saldo = Mvto::where('product_id', $product->id)->where('unit2_id', $product->unit_id)->where('cant2', '<>', 0)->sum('cant2');
                    $p = json_decode(collect(['id' => $product->id, 
                                                'product_name' => $product->name,
                                                'unit_id' => $product->unit_id,
                                                'unit_name' => $product->unit->name,
                                                'cant' => 0.0,
                                                'saldo' => $saldo
                                            ]));
                    $products->push($p);
                }
                $p->cant += $this->unitConversionService->convert($recipe->cant / $product->factor, $recipe->unit_id, $p->unit_id)*$cant_diet;
            }
        }

        return $products;
    }

    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()
            ->addColumn('id')
            ->addColumn('product_name')
            ->addColumn('unit_name')
            ->addColumn('cant', fn ($m) => number_format($m->cant, 4))
            ->addColumn('saldo', fn ($m) => number_format($m->saldo, 4))
            ->addColumn('shop', fn ($m) => number_format($m->saldo < $m->cant ?  $m->cant - $m->saldo : 0, 4));
    }

    public function columns(): array
    {
        return [
            Column::make('Producto', 'product_name'),

            Column::make('Unidad', 'unit_name'),

            Column::make('Requerido', 'cant'),

            Column::make('Inventario', 'saldo'),

            Column::make('Comprar', 'shop'),
        ];
    }
}
