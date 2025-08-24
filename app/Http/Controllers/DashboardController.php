<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        return view('dashboard.index');
    }
    public function supplier(){
        $heads = ['CI', 'Nombre Proveedor', 'Contacto Principal', 'Categoria', 'Acciones'];
        $config = ['columns' => [null, null, null, null, ['orderable' => false, 'searchable' => false]]];
        return view('dashboard.supplier-view', compact(['heads', 'config']));
    }
}
