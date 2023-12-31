<?php

namespace App\Http\Controllers\master;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Item;
use App\Models\Mvto;
use App\Models\Product;
use App\Models\Unit;
use App\Services\UnitConversionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    protected $unitConversionService;

    public function __construct(UnitConversionService $unitConversionService)
    {
        $this->unitConversionService = $unitConversionService;
    }
    
    public function index()
    {
        return view('master.product.index');
    }

    public function create()
    {
        $units = Unit::where('state', 1)->orderBy('name')->get();
        $categories = Item::where('catalog_id', 203)->orderBy('order')->orderBy('name')->get()->prepend(['id' => null, 'name' => '-']);
        $unit_selected = Unit::where('name', 'LIKE', 'gramo%')->first();
        $unit_selected = $unit_selected->id ?? null;

        return view('master.product.create', compact('units', 'categories', 'unit_selected'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' =>'required|string|max:255',
            'unit_id' =>'required|integer',
        ]);

        DB::beginTransaction();
        try {            
            Product::create([
                'code' => $request->code,
                'name' => $request->name,
                'factor' => $request->factor,
                'unit_id' => $request->unit_id,
                'isinventory' => $request->isinventory ?? 0,
                'state' => $request->state ?? 0,
                'class' => $request->class,
            ]);

            DB::commit();
            return redirect()->route('product.index')->with('success', 'Se ha registrado el producto correctamente.');
        }
        catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error al registrar el producto');
        }
    }
    
    public function edit(Product $product)
    {
        $units = Unit::where('state', 1)->orderBy('name')->get();
        $categories = Item::where('catalog_id', 203)->orderBy('order')->orderBy('name')->get()->prepend(['id' => null, 'name' => '-']);
        $unit_selected = $product->unit->id;

        return view('master.product.edit', compact('product', 'units', 'categories', 'unit_selected'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' =>'required|string|max:255',
            'unit_id' =>'required|integer',
        ]);

        DB::beginTransaction();
        try {
            if ($product->unit_id != $request->unit_id) 
            {
                $factor_unit = $this->unitConversionService->convert(1, $product->unit_id, $request->unit_id);
                
                if ($factor_unit != 0)
                {
                    Mvto::where(['product_id' => $product->id, 'unit2_id' => $product->unit_id])
                        ->where('cant2', '<>', 0)->update([
                        'unit2_id' => $request->unit_id,
                        'cant2' => DB::raw('cant2 * ' . $factor_unit),
                        'valueu2' => DB::raw('valueu2 / ' . $factor_unit),
                    ]);
                }
            }

            $product->update([
                'code' => $request->code,
                'name' => $request->name,
                'factor' => $request->factor,
                'unit_id' => $request->unit_id,
                'isinventory' => $request->isinventory ?? 0,
                'state' => $request->state ?? 0,
                'class' => $request->class,
            ]);

            DB::commit();
            return redirect()->route('product.index')->with('success', 'Se ha actualizado el producto correctamente.');
        }
        catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error al actualizar el producto');
        }
    }

    public function editImage(Product $product)
    {
        return view('master.product.editImage', compact('product'));
    }

    public function updateImage(Request $request, Product $product)
    {
        $request->validate([
            'image' => 'required|file|mimes:jpeg,jpg,png|max:2048',
        ]);
        
        DB::beginTransaction();
        try {
            $imagename = $request->file('image')->store('/producto', 'public');

            $image = Image::where('imageable_id', $product->id)->where('imageable_type', 'App\Models\Product')->first();

            if ($image) {
                $image->url = $imagename;            
                $image->save();
            }
            else
            {
                $image = Image::create([
                    'url' => $imagename,
                    'imageable_id' => $product->id,
                    'imageable_type' => 'App\Models\Product',
                ]);                
            }  
                     
            DB::commit();
            if ($product->type == 1)
                return redirect()->route('diet.index')->with('success', 'Se ha actualizado la imagen del producto correctamente.');
            else 
                return redirect()->route('product.index')->with('success', 'Se ha actualizado la imagen del producto correctamente.');
        }
        catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Ha ocurrido un error al actualizar la imagen del producto');
        }
    }
}
