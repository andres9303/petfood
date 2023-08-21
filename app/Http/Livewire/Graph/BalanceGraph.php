<?php

namespace App\Http\Livewire\Graph;

use App\Models\Doc;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class BalanceGraph extends Component
{
    public $labels = [];
    public $costs = [];
    public $bills = [];
    public $sales = [];
    public $inventory = [];

    public function render()
    {
        return view('livewire.graph.balance-graph');
    }

    public function mount()
    {
        $data = Doc::query()
            ->join('mvtos', 'mvtos.doc_id', '=', 'docs.id')
            ->where('docs.date', '>=', Carbon::now()->subMonths(6)->startOfMonth())
            ->groupBy(DB::raw('month, year'))
            ->orderBy('year')
            ->orderBy('month')
            ->selectRaw("
                YEAR(docs.date) AS year,
                MONTH(docs.date) AS month,
                SUM(CASE WHEN (docs.menu_id = 502 AND mvtos.concept=50202) OR (docs.menu_id = 604) THEN mvtos.valuet2 ELSE 0 END) AS cost,
                SUM(CASE WHEN docs.menu_id = 603 THEN mvtos.valuet ELSE 0 END) AS bill,
                SUM(CASE WHEN docs.menu_id = 503 THEN mvtos.valuet ELSE 0 END) AS sale,
                (SELECT SUM(mvtos_inv.valuet2) 
                    FROM docs as docs_inv
                        INNER JOIN mvtos as mvtos_inv ON docs_inv.id = mvtos_inv.doc_id
                    WHERE YEAR(docs_inv.date) <= year AND MONTH(docs_inv.date) <= month
                        AND mvtos_inv.cant2 <> 0) AS inv
            ")->get();
        
        foreach ($data as $item) {
            $this->labels[] = "{$item->year}-{$item->month}";
            $this->costs[] = $item->cost*-1;
            $this->bills[] = $item->bill;
            $this->sales[] = $item->sale;
            $this->inventory[] = $item->inv;
        }
    }
}
