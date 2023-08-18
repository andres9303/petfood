<?php

namespace App\Http\Controllers\master;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecipeController extends Controller
{
    public function index(Product $diet)
    {
        return view('master.diet.recipe.index', compact('diet'));
    }

    public function destroy(Product $diet, Product $recipe, Request $request)
    {
        DB::beginTransaction();
        try
        {
            Recipe::where([
                'product_id' => $diet->id,
                'ref_id' => $recipe->id,
                'unit_id' => $request->unit,
            ])->delete();
            
            DB::commit();
            return redirect()->route('diet.recipe.index', $diet->id)->with('success', 'Se eliminó el ingrediente de la dieta.');
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return redirect()->route('diet.recipe.index', $diet->id)->with('error', 'No se pudo eliminar el ingrediente de la dieta.');
        }
    }
}
