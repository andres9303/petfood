<?php

namespace App\Http\Controllers\report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function inventory()
    {
        return view('report.inventory');
    }

    public function movements()
    {
        return view('report.movements');
    }

    public function order()
    {
        return view('report.order');
    }

    public function purchase()
    {
        return view('report.purchase');
    }

    public function bill()
    {
        return view('report.bill');
    }

    public function production()
    {
        return view('report.production');
    }

    public function sale()
    {
        return view('report.sale');
    }

    public function dispatch()
    {
        return view('report.dispatch');
    }

    public function cost()
    {
        return view('report.cost');
    }

    public function balance()
    {
        return view('report.balance');
    }
}
