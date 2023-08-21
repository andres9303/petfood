<?php

namespace App\Http\Livewire\Graph;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class InventoryGraph extends Component
{
    public $categories = [];
    public $inventoryValues = [];

    public function render()
    {
        return view('livewire.graph.inventory-graph');
    }

    public function mount()
    {
        $inventoryByCategory = DB::table('mvtos')
            ->join('products', 'mvtos.product_id', '=', 'products.id')
            ->join('items', 'products.class', '=', 'items.id')
            ->select('items.name as category', DB::raw('SUM(mvtos.valuet2) as inventory'))
            ->groupBy('items.name')
            ->get();

        $this->categories = $inventoryByCategory->pluck('category');
        $this->inventoryValues = $inventoryByCategory->pluck('inventory');
    }
}
