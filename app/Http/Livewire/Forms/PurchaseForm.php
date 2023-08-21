<?php

namespace App\Http\Livewire\Forms;

use App\Models\Doc;
use App\Models\Item;
use App\Models\Mvto;
use App\Models\Order;
use App\Models\Product;
use App\Models\Unit;
use App\Services\UnitConversionService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PurchaseForm extends Component
{
    protected $unitConversionService;

    public $doc;
    public $orders;
    public $categories;
    public $units;
    public $unit_base;
    public $products;
    public $products_base;
    public $search;
    public $showModal = false;

    public $subtotal = 0;
    public $tax = 0;
    public $total = 0;
    
    public $error;
    public $cant = 1;
    public $unit_id;
    public $valueu = 0;
    public $iva = 0;
    public $text;

    public function render()
    {
        return view('livewire.forms.purchase-form');
    }

    public function mount(Doc $doc)
    {        
        $this->doc = $doc;

        $this->categories = Item::where('catalog_id', 203)->orderBy('name', 'ASC')->get(); 
        $this->units = Unit::where('state', 1)->orderBy('name', 'ASC')->get();
        $this->products_base = Product::where('state', 1)->where('isinventory', 1)->get();
        $this->products = $this->products_base;

        $this->unit_base = Unit::where('name', 'LIKE', 'gramo%')->first();
        $this->unit_id = $this->unit_base ? $this->unit_base->id : $this->units->first()->id;

        self::calculateTotals();
    }

    public function changeCategory($category_id)
    {
        if ($category_id > 0)
            $this->products = $this->products_base->where('class', $category_id);
        else
            $this->products = $this->products_base;
    }

    public function updatedSearch()
    {
        $this->products = $this->products_base->filter(function($product) {
            return strpos(strtolower($product->name), strtolower($this->search)) !== false;
        });
    }

    public function changeModal($view)
    {
        $this->showModal = $view;
        $this->search = null;
        $this->products = $this->products_base;        
    }

    public function selectProduct($product_id)
    {
        if ($product_id > 0 && $this->cant > 0 && $this->valueu > 0)
        {
            $this->unitConversionService = app(UnitConversionService::class);
            $product = Product::find($product_id);
            if ($this->unitConversionService->convert($this->cant, $this->unit_id, $product->unit_id) == 0)
                $this->error = 'La unidad selecciona no se puede convertir a la unidad base del producto.';
            else
            {
                DB::beginTransaction();
                try
                {                    
                    if ($this->doc && $this->doc->id)
                    {
                        $valuet = $this->cant * $this->valueu * (1 + $this->iva / 100);
                        $cant_base = $this->unitConversionService->convert($this->cant, $this->unit_id, $product->unit_id);
                        $valueu_base = $this->valueu / $cant_base;

                        Mvto::create([
                            'doc_id' => $this->doc->id,
                            'product_id' => $product_id,
                            'cant' => $this->cant,
                            'unit_id' => $this->unit_id,
                            'valueu' => $this->valueu,
                            'iva' => $this->iva,
                            'valuet' => $valuet,
                            'unit2_id' => $product->unit_id,
                            'cant2' => $cant_base,
                            'valueu2' => $valueu_base,
                            'iva2' => $this->iva,
                            'valuet2' => $valuet,
                            'text' => $this->text,
                        ]);
                    }
                    else
                    {
                        Order::create([
                            'menu_id' => 602,
                            'product_id' => $product_id,
                            'cant' => $this->cant,
                            'unit_id' => $this->unit_id,
                            'valueu' => $this->valueu,
                            'iva' => $this->iva,
                            'text' => $this->text,
                        ]);
                    }
                    DB::commit();
                    $this->cant = 1;
                    $this->valueu = 0;
                    $this->iva = 0;
                    $this->text = null;
                    $this->error = null;

                    $this->unit_id = $this->unit_base ? $this->unit_base->id : $this->units->first()->id;
                    $this->changeModal(false);
                    self::calculateTotals();
                }
                catch (\Exception $e) {
                    DB::rollBack();
                    $this->error = 'Ha ocurrido un error al agregar el producto'. $e->getMessage();
                }
            }
        }
        else
        {
            $this->error = 'Debe seleccionar un producto con su cantidad, unidad y valor unitario para agregar.';
        }
    }

    public function removeProduct($order_id)
    {
        DB::beginTransaction();
        try
        {
            if ($this->doc && $this->doc->id)
            {
                
                Mvto::find($order_id)->update([
                    'cant' => 0,
                    'valuet' => 0,
                    'cant2' => 0,
                    'valuet2' => 0,
                    'state' => -1,
                ]);
            }
            else
            {
                Order::find($order_id)->delete();
            }
            DB::commit();            
            self::calculateTotals();
        }
        catch (\Exception $e) {
            DB::rollBack();
        }
    }

    public function calculateTotals()
    {
        $this->doc->refresh();
        $this->orders = $this->doc && $this->doc->id ? $this->doc->mvtos->where('state', 1) : Order::where('menu_id', 602)->get();
        $sub = 0;
        $taxes = 0;
        foreach ($this->orders as $order) {
            $valuet = $order->valueu * $order->cant;
            $sub += $valuet;
            $taxes += ($valuet * ($order->iva / 100));
        }

        $this->subtotal = $sub;
        $this->tax = $taxes;
        $this->total = $this->subtotal + $this->tax;
    }
}
