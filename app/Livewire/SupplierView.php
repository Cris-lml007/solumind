<?php

namespace App\Livewire;

use App\Models\Supplier;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class SupplierView extends Component
{
    public function render()
    {
        if(!Gate::allows('supplier-read'))
            abort('404');
        $heads = ['ID', 'NIT', 'Nombre Proveedor', 'Contacto Principal', 'Acciones'];
        $config = ['columns' => [null, null, null, null, ['orderable' => false, 'searchable' => false]]];
        $data = Supplier::all();
        return view('livewire.supplier-view', compact(['heads', 'config','data']));
    }
}
