<?php

namespace App\Http\Livewire\Forms;

use App\Models\Doc;
use App\Models\Mvto;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ProduceForm extends Component
{
    public $produce;
    public $diets;
    public $recipes;

    public $mvto_selected;
    public $cant_selected;

    public $showModal = false;
    public $error;

    public function render()
    {
        return view('livewire.forms.produce-form');
    }

    public function mount(Doc $produce)
    {
        $this->produce = $produce;
        $this->diets = $produce->mvtos()->where('mvtos.concept', 50201)->get();
        $this->recipes = $produce->mvtos()->where('mvtos.concept', 50202)->get();
    }

    public function edit($mvto_id)
    {
        $this->mvto_selected = Mvto::find($mvto_id);
        $this->cant_selected = $this->mvto_selected->cant;
        $this->showModal = true;
    }

    public function update()
    {
        DB::beginTransaction();
        try
        {
            $cant = $this->cant_selected;
            if ($this->mvto_selected->concept == 50202)
                $cant = $cant > 0 ? $cant * -1 : $cant;

            $this->mvto_selected->cant = $cant;
            $this->mvto_selected->save();

            if ($this->produce->state == 1)
            {
                $this->produce->state = 0;
                $this->produce->save();
                $this->produce->refresh();
            }      

            $this->diets = $this->produce->mvtos()->where('mvtos.concept', 50201)->get();
            $this->recipes = $this->produce->mvtos()->where('mvtos.concept', 50202)->get();
            $this->changeModal();

            DB::commit();
        }
        catch (\Exception $e)
        {
            DB::rollback();            
            $this->error = "Se ha producido un error al actualizar la dieta.";
            self::changeModal();
        }        
    }

    public function changeModal()
    {
        $this->showModal = false;
        $this->mvto_selected = null;
        $this->cant_selected = null;
    }
}
