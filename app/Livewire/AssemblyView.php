<?php

namespace App\Livewire;

use App\Models\Item;
use Livewire\Component;

class AssemblyView extends Component
{

    public $listeners = ['render'];


    public function messagea(){
        dd("hola como estas");
    }


    public function render()
    {
        $heads = ['ID','Codigo', 'Nombre de Producto', 'Precio', 'Acciones'];
        $config = ['columns' => [null, null, null, null, ['orderable' => false, 'searchable' => false]]];
        $data = Item::all();
        $this->dispatch('close-modal');
        return view('livewire.assembly-view',compact(['heads','config','data']));
    }
}
