<?php

namespace App\Http\Controllers;

use App\Models\Doc;
use App\Models\Menu;
use App\Models\Mvto;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $user = User::find(auth()->user()->id);
        $roles = $user->roles()->get();

        $shortcuts = Menu::whereIn('id', function($query) use ($roles) {
                            $query->select('menu_id')
                                ->from('shortcuts')
                                ->whereIn('role_id', $roles->pluck('id'));
                        })->distinct()->get();

        $value_inventory = $this->valueInventory();
        $value_sale = $this->valueSaleMonth();
        $value_bill = $this->valueBillMonth();
        $value_cost = $this->valueCostMonth()*-1;

        return view('dashboard', compact('shortcuts', 'value_inventory', 'value_sale', 'value_bill', 'value_cost'));
    }

    protected function valueInventory()
    {
        return Mvto::where('cant2', '<>', 0)->sum('valuet2');
    }

    protected function valueSaleMonth()
    {
        return Doc::join('mvtos', 'mvtos.doc_id', '=', 'docs.id')
            ->where('docs.menu_id', 503)
            ->whereMonth('docs.date', Carbon::now()->month)
            ->sum('mvtos.valuet');
    }

    protected function valueBillMonth()
    {
        return Doc::join('mvtos', 'mvtos.doc_id', '=', 'docs.id')
            ->where('docs.menu_id', 603)
            ->whereMonth('docs.date', Carbon::now()->month)
            ->sum('mvtos.valuet');
    }

    protected function valueCostMonth()
    {
        return Doc::join('mvtos', 'mvtos.doc_id', '=', 'docs.id')
            ->where(function ($query) {
                $query->where(['docs.menu_id' => 502, 'mvtos.concept' => 50202])
                    ->orWhere('docs.menu_id', 604);
            })
            ->whereMonth('docs.date', Carbon::now()->month)
            ->sum('mvtos.valuet2');
    }
}
