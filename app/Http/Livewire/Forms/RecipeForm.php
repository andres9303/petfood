<?php

namespace App\Http\Livewire\Forms;

use App\Models\Item;
use App\Models\Product;
use App\Models\Recipe;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class RecipeForm extends Component
{
    public $diet;
    public $categories;
    public $units;
    public $products;
    public $products_base;
    public $search;
    public $showModal = false;
    
    public $error;
    public $cant = 1;
    public $unit_id;
    public $text;

    public function render()
    {
        return view('livewire.forms.recipe-form');
    }

    public function mount(Product $diet)
    {
        $this->diet = $diet;
        $this->categories = Item::where('catalog_id', 203)->orderBy('name', 'ASC')->get(); 
        $this->units = Unit::where('state', 1)->orderBy('name', 'ASC')->get();
        $this->products_base = Product::where('state', 1)->where('isinventory', 1)->get();
        $this->products = $this->products_base;

        $unit = Unit::where('name', 'LIKE', 'gramo%')->first();
        $this->unit_id = $unit ? $unit->id : null;
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

    public function addCant($cant)
    {
        if ($this->cant + $cant >= 0)
            $this->cant += $cant;
    }

    public function changeModal($view)
    {
        $this->showModal = $view;
        $this->search = null;
        $this->products = $this->products_base;        
    }

    public function selectProduct($product_id)
    {
        if ($product_id > 0 && $this->cant != null)
        {
            DB::beginTransaction();
            try {
                Recipe::create([
                    'product_id' => $this->diet->id,
                    'ref_id' => $product_id,
                    'unit_id' => $this->unit_id,
                    'cant' => $this->cant,
                    'text' => $this->text
                ]);
                DB::commit();
                $this->changeModal(false);
                $this->cant = 1;
                $unit = Unit::where('name', 'LIKE', 'gramo%')->first();
                $this->unit_id = $unit ? $unit->id : null;
                $this->text = null;
                $this->error = null;
                redirect()->route('diet.recipe.index', $this->diet)->with('success', 'Se agregó el ingrediente de la dieta.');
            }            
            catch (\Exception $e) {
                DB::rollBack();
                $this->error = 'Ha ocurrido un error, el producto ya ha sido agregado, si necesita modificarlo, elimínelo y vuelva a agregarlo.';
            }
        }
    }
}
