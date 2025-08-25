<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        return view('dashboard.index');
    }
    public function supplier(){
        $heads = ['NIT', 'Nombre Proveedor', 'Contacto Principal', 'Acciones'];
        $config = ['columns' => [null, null, null, ['orderable' => false, 'searchable' => false]]];
        $data = Supplier::all();
        return view('dashboard.supplier-view', compact(['heads', 'config','data']));
    }

    public function product(){
        $heads = ['ID', 'Nombre Producto', 'Proveedor', 'Precio (Bs)', 'Acciones'];
        $config = ['columns' => [null, null, null, null, ['orderable' => false, 'searchable' => false]]];
        $data = Product::all();
        return view('dashboard.product-view', compact(['heads', 'config','data']));
    }

    public function assembly(){
        $heads = ['Codigo', 'Nombre de Producto', 'Precio', 'Acciones'];
        $config = ['columns' => [null, null, null, ['orderable' => false, 'searchable' => false]]];
        $data = Item::all();
        return view('dashboard.assembly-view',compact(['heads','config','data']));
    }
}
