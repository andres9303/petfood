<?php

namespace App\Http\Controllers\shopping;

use App\Http\Controllers\Controller;
use App\Models\Doc;
use Illuminate\Http\Request;

class ShoppingListController extends Controller
{
    public function index(Request $request)
    {
        $reqs = $request->reqs ? implode(',', $request->reqs) : '';
        $orders = Doc::where('menu_id', 501)->where('state', 0)->orderBy('date')->get();

        return view('shopping.list.index', compact('reqs', 'orders'));
    }
}
