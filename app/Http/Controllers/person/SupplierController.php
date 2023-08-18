<?php

namespace App\Http\Controllers\person;

use App\Http\Controllers\Controller;
use App\Models\Person;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        return view('person.supplier.index');
    }

    public function create()
    {
        return view('person.supplier.create');
    }

    public function edit(Person $supplier)
    {
        return view('person.supplier.edit', compact('supplier'));
    }
}
