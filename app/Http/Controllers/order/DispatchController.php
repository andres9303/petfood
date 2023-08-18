<?php

namespace App\Http\Controllers\order;

use App\Http\Controllers\Controller;
use App\Models\Doc;
use App\Models\Mvto;
use App\Services\UnitConversionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DispatchController extends Controller
{
    protected $unitConversionService;

    public function __construct(UnitConversionService $unitConversionService)
    {
        $this->unitConversionService = $unitConversionService;
    }
    
    public function index()
    {
        return view('order.dispatch.index');
    }

    public function create(Request $request)
    {
        $request->validate([
            'produce' => 'required|integer|exists:docs,id',
        ]);

        $produce = Doc::find($request->produce);
        if ($produce->doc_id)
            return redirect()->route('dispatch.edit', ['produce' => $produce->id, 'dispatch' => $produce->doc_id]);
        $now = date('Y-m-d');
        
        return view('order.dispatch.create', compact('produce', 'now'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'produce' => 'required|integer|exists:docs,id',
        ]);

        $produce = Doc::find($request->produce);
        if ($produce->doc_id)
            return redirect()->route('dispatch.edit', ['produce' => $produce->id, 'dispatch' => $produce->doc_id]);

        DB::beginTransaction();
        try
        {
            $dispatch = Doc::create([
                'type' => 'DSP',
                'menu_id' => 503,
                'num' => Doc::where('menu_id', 503)->max('num') + 1,
                'date' => $request->date,
                'state' => 1,
                'user_id' => auth()->user()->id,
                'text' => $request->text,
            ]);

            $orders = Doc::where('doc_id', $produce->id)->get();
            foreach ($orders as $order)
            {
                $cant = $order->mvtos[0]->cant*$order->mvtos[0]->cant_src;
                if ($cant > 0)
                {
                    $cant_base = $this->unitConversionService->convert($cant, $order->mvtos[0]->unit_id, $order->mvtos[0]->product->unit_id);
                    $valuet = $order->mvtos[0]->product->valueu * $cant_base;
                    
                    Mvto::create([
                        'doc_id' => $dispatch->id,
                        'product_id' => $order->mvtos[0]->product_id,
                        'unit_id' => $order->mvtos[0]->unit_id,
                        'cant' => $cant,
                        'cant_src' => $order->mvtos[0]->cant_src,
                        'valueu' => $valuet / $cant,
                        'valuet' => $valuet,
                        'mvto_id' => $order->mvtos[0]->id,
                    ]);
                }                
            }

            $produce->update([
                'doc_id' => $dispatch->id,
                'state' => 2,
            ]);

            DB::commit();
            return redirect()->route('dispatch.edit', ['produce' => $produce->id, 'dispatch' => $dispatch->id])->with('success', 'despacho creado con éxito');
        }
        catch (\Exception $e)
        {
            DB::rollback();
            return redirect()->route('dispatch.create', ['produce' => $produce->id])->with('error', 'error al crear el despacho.'.$e->getMessage());
        }
    }

    public function edit(Request $request, Doc $dispatch)
    {
        $request->validate([
            'produce' => 'required|integer|exists:docs,id',
        ]);

        $produce = Doc::find($request->produce);
        $now = Carbon::parse($dispatch->date)->format('Y-m-d');

        return view('order.dispatch.edit', compact('dispatch', 'produce', 'now'));
    }

    public function update(Request $request, Doc $dispatch)
    {
        
    }

    public function destroy(Doc $dispatch)
    {
        
    }
}
