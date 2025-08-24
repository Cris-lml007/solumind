<?php

namespace App\Livewire;

use Livewire\Component;

class SupplierForm extends Component
{

    public function show(){
        $this->js("alert('Hello World');");
    }
    public function render()
    {
        return view('livewire.supplier-form');
    }
}
