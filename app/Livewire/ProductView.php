<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;

class ProductView extends Component
{
    public function render()
    {
        $heads = ['ID', 'Nombre Producto', 'Proveedor', 'Precio (Bs)', 'Acciones'];
        $config = ['columns' => [null, null, null, null, ['orderable' => false, 'searchable' => false]]];
        $data = Product::all();
        return view('livewire.product-view', compact(['heads', 'config','data']));
    }
}
