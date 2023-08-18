<?php

namespace App\Http\Livewire\Forms;

use App\Models\Doc;
use App\Models\Mvto;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DispatchForm extends Component
{
    public $dispatch;
    public $produce;
    public $orders;

    public $order_selected;
    public $cant_selected;
    public $valueu_selected;

    public $showModal = false;
    public $error;

    public function render()
    {
        return view('livewire.forms.dispatch-form');
    }

    public function mount(Doc $dispatch)
    {
        $this->dispatch = $dispatch;
        $this->produce = Doc::where('doc_id', $dispatch->id)->first();
        $this->orders = $this->dispatch->mvtos()->get();
    }

    public function edit($mvto_id)
    {
        $this->order_selected = Mvto::find($mvto_id);
        $this->cant_selected = $this->order_selected->cant;
        $this->valueu_selected = $this->order_selected->valueu;
        $this->showModal = true;
    }

    public function update()
    {
        DB::beginTransaction();
        try
        {
            $cant = $this->cant_selected < 0 ? $this->cant_selected * -1 : $this->cant_selected;

            $this->order_selected->cant = $cant;
            $this->order_selected->valueu = $this->valueu_selected;
            $this->order_selected->valuet = $cant * $this->valueu_selected;
            $this->order_selected->save();

            $this->orders = $this->dispatch->mvtos()->get();
            $this->changeModal();

            DB::commit();
        }
        catch (\Exception $e)
        {
            DB::rollback();            
            $this->error = "Se ha producido un error al actualizar la cantidad despachada.";
            self::changeModal();
        }        
    }

    public function changeModal()
    {
        $this->showModal = false;
        $this->order_selected = null;
        $this->cant_selected = null;
        $this->valueu_selected = null;
    }
}
