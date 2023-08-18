<?php

namespace App\Http\Controllers\order;

use App\Http\Controllers\Controller;
use App\Models\Doc;
use App\Models\Mvto;
use App\Models\Product;
use App\Services\UnitConversionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProduceController extends Controller
{
    protected $unitConversionService;

    public function __construct(UnitConversionService $unitConversionService)
    {
        $this->unitConversionService = $unitConversionService;
    }

    public function index()
    {
        return view('order.produce.index');
    }

    public function create()
    {
        $now = date('Y-m-d');
        $orders = Doc::where('menu_id', 501)->where('state', 0)->orderBy('date')->get();

        return view('order.produce.create', compact('orders', 'now'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required',         
        ]);

        DB::beginTransaction();
        try
        {
            $produce = Doc::create([
                'type' => 'PRP',
                'menu_id' => 502,
                'code' => 'PRP',
                'num' => Doc::where('menu_id', 502)->max('num') + 1,
                'date' => $request->date,
                'text' => $request->text,
                'state' => 0,
                'user_id' => auth()->user()->id
            ]);
            
            $reqs = $request->reqs;
            if ($reqs)
            {
                $diets = $this->generateMvtoDiet($produce, $reqs);
                $this->generateMvtoProduct($produce, $diets);
            }

            DB::commit();
            return redirect()->route('produce.show', ['produce' => $produce])->with('success', 'Registro creado satisfactoriamente');
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return redirect()->route('produce.create')->with('error', 'Error al crear el registro');
        }
    }

    public function show(Doc $produce)
    {
        return view('order.produce.show', compact('produce'));
    }

    public function edit(Doc $produce)
    {
        $now = Carbon::parse($produce->date)->format('Y-m-d');
        $orders = Doc::where('menu_id', 501)->where('state', 0)->orderBy('id')->get();
        $orders = $orders->merge(Doc::where('menu_id', 501)->where('doc_id', $produce->id)->orderBy('id')->get());

        return view('order.produce.edit', compact('produce', 'orders', 'now'));
    }

    public function update(Request $request, Doc $produce)
    {
        $request->validate([
            'date' => 'required',
        ]);

        DB::beginTransaction();
        try
        {
            if ($produce->state == 1)
                return redirect()->route('produce.edit', ['produce' => $produce])->with('error', 'El registro no se puede modificar porque ya fue procesado');

            $produce->update([
                'date' => $request->date,
                'text' => $request->text,
            ]);

            Doc::where('doc_id', $produce->id)->update([
                'state' => 0,
                'doc_id' => null,
            ]);

            Mvto::where('doc_id', $produce->id)->update([
                'cant' => 0,
                'cant_src' => 0,
                'state' => 0,
            ]);

            $reqs = $request->reqs;
            if ($reqs)
            {
                $diets = $this->generateMvtoDiet($produce, $reqs);
                $this->generateMvtoProduct($produce, $diets);
            }

            DB::commit();
            return redirect()->route('produce.show', ['produce' => $produce])->with('success', 'Registro actualizado satisfactoriamente');
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return redirect()->route('produce.edit', ['produce' => $produce])->with('error', 'Error al actualizar el registro');
        }
    }

    public function destroy(Doc $produce)
    {
        if ($produce->state == 1)
            return redirect()->route('produce.index')->with('error', 'El registro no se puede eliminar porque ya fue procesado');

        DB::beginTransaction();
        try
        {
            Doc::where('doc_id', $produce->id)->update([
                'state' => 0,
                'doc_id' => null,
            ]);

            Mvto::where('doc_id', $produce->id)->update([
                'cant' => 0,
                'cant_src' => 0,
                'state' => 0,
            ]);

            $produce->update([
                'state' => -1,
            ]);

            DB::commit();
            return redirect()->route('produce.index')->with('success', 'Registro eliminado satisfactoriamente');
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return redirect()->route('produce.index')->with('error', 'Error al eliminar el registro');
        }
    }

    public function editComplete(Doc $produce)  
    {
        return view('order.produce.editComplete', compact('produce'));
    }

    public function updateComplete(Request $request, Doc $produce)  
    {
        DB::beginTransaction();
        try
        {
            $produce->update([
                'state' => 1,
            ]);

            $mvtos = Mvto::where(['doc_id' => $produce->id, 'concept' => 50202])->get();
            foreach ($mvtos as $mvto) 
            {
                $cant_inv = Mvto::where(['product_id' => $mvto->product_id, 'unit2_id' => $mvto->product->unit_id])->where('id', '<>', $mvto->id)->sum('cant2');
                $cant_inv = $cant_inv > 0 ? $cant_inv : 1;

                $valueu = Mvto::where(['product_id' => $mvto->product_id, 'unit2_id' => $mvto->product->unit_id])->sum('valuet2') / $cant_inv;
                $cant_base = $this->unitConversionService->convert($mvto->cant / $mvto->product->factor, $mvto->unit_id, $mvto->product->unit_id);
                
                $mvto->update([
                    'unit2_id' => $mvto->product->unit_id,
                    'cant2' => $cant_base,
                    'valueu2' => $valueu,
                    'iva2' => 0,
                    'valuet2' => $valueu * $cant_base,
                ]);
            }

            DB::commit();
            return redirect()->route('produce.show', ['produce' => $produce])->with('success', 'Registro actualizado satisfactoriamente');
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return redirect()->route('produce.editComplete', ['produce' => $produce])->with('error', 'Error al actualizar el registro');
        }
    }

    /**
     * Genera los movimientos de dietas para los pedidos de la producción
     *
     * @param  \App\Models\Doc  $produce
     * @param  array  $reqs
     * @return array $diets
     */
    protected function generateMvtoDiet(Doc $produce, $reqs)
    {
        $diets = collect([]);
        foreach ($reqs as $order_id) 
        {
            $order = Doc::find($order_id);
            $order->update([
                'state' => 1,
                'doc_id' => $produce->id,
            ]);

            $diet = Product::find($order->mvtos[0]->product_id);
            $d = $diets->where('id', $diet->id)->first();
            if ($d == null)
            {
                $d = json_decode(collect(['id' => $diet->id, 
                                            'unit_id' => $diet->unit_id,
                                            'cant' => 0.0,
                                            'cant_src' => 0.0,
                                        ]));
                $diets->push($d);
            }
            $cant = $this->unitConversionService->convert($order->mvtos[0]->cant*$order->mvtos[0]->cant_src, $order->mvtos[0]->unit_id, $diet->unit_id);
            $d->cant += $cant; 
            $d->cant_src += $order->mvtos[0]->cant_src;                   
        }
        $this->insertMvto($produce, $diets, 1, 50201);

        return $diets;
    }

    /**
     * Genera los movimientos de los productos que se consumen las dietas para los pedidos de la producción
     *
     * @param  \App\Models\Doc  $produce
     * @param  array  $diets
     * @return array $products
     */
    protected function generateMvtoProduct(Doc $produce, $diets)
    {
        $products = collect([]);
        foreach ($diets as $diet)
        {
            $d = Product::find($diet->id);
            foreach($d->recipes as $recipe)
            {
                $p = $products->where('id', $recipe->ref_id)->first();
                if ($p == null)
                {
                    $product = Product::find($recipe->ref_id);
                    $p = json_decode(collect(['id' => $product->id, 
                                                'unit_id' => $product->unit_id,
                                                'cant' => 0.0,
                                                'cant_src' => 0.0,
                                            ]));
                    $products->push($p);
                }
                $p->cant += $this->unitConversionService->convert($recipe->cant, $recipe->unit_id, $p->unit_id)*$diet->cant;
            }
        }
        $this->insertMvto($produce, $products, -1, 50202);

        return $products;
    }

    /**
     * Inserta en la base de datos los Mvtos
     *
     * @param  \App\Models\Doc  $produce
     * @param  array  $arr
     * @param  float  $factor
     * @param  string  $concept
     * @return array $products
     */
    protected function insertMvto(Doc $produce, $arr, $factor, $concept)
    {
        foreach ($arr as $a) 
        {
            $mvto = Mvto::where([
                    'doc_id' => $produce->id, 
                    'product_id' => $a->id, 
                    'unit_id' => $a->unit_id, 
                    'concept' => $concept,
                ])->first();

            if ($mvto)
                $mvto->update([
                    'cant' => $a->cant * $factor,
                    'cant_src' => $a->cant_src,
                    'state' => 1,
                ]);
            else
                Mvto::create([
                    'doc_id' => $produce->id,
                    'product_id' => $a->id,
                    'unit_id' => $a->unit_id,
                    'cant' => $a->cant * $factor,
                    'cant_src' => $a->cant_src,
                    'state' => 1,
                    'concept' => $concept,
                ]);
        }
    }
}
