<?php

namespace App\Http\Livewire\Forms;

use App\Models\Person;
use Livewire\Component;

class PersonForm extends Component
{
    public $code;
    public $name;
    public $address;
    public $phone;
    public $email;
    public $birth;

    public function render()
    {
        return view('livewire.forms.person-form');
    }

    public function mount(Person $person)
    {
        if ($person)
        {
            $this->code = $person->code;
            $this->name = $person->name;
            $this->address = $person->address;
            $this->phone = $person->phone;
            $this->email = $person->email;
            $this->birth = $person->birth;
        }
    }

    public function updatedCode($value)
    {
        $person = Person::where('code', $value)->first();

        if ($person)
        {
            $this->name = $person->name;
            $this->address = $person->address;
            $this->phone = $person->phone;
            $this->email = $person->email;
            $this->birth = $person->birth;
        }
        else
        {
            $this->name = null;
            $this->address = null;
            $this->phone = null;
            $this->email = null;
            $this->birth = null;
        }        
    }
}
