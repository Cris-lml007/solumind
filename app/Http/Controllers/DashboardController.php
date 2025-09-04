<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Category;
use App\Models\Item;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Client;
use App\Models\Contract;
use App\Models\User;
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

    public function partner(){
        $heads = ['CI','Nombre Completo','Contacto Principal','Acciones'];
        $config = ['columns' => [null, null, null, ['orderable' => false, 'searchable' => false]]];
        $data = Partner::all();
        return view('dashboard.partner-view',compact(['heads','config','data']));
    }

    public function client(){
        $heads = ['CI', 'NIT', 'Nombre Cliente', 'Contacto Principal', 'Acciones'];
        $config = ['columns' => [null, null, null, null, null, ['orderable' => false, 'searchable' => false]]];
        $data = Client::all();
        return view('dashboard.client-view', compact(['heads', 'config','data']));
    }

    public function proof(){
        $config = ['columns' => [null, null, null, null, null, ['orderable' => false, 'searchable' => false]]];
        $data = [
            'comprobantes' => [
                (object)['id' => 1,
                'fecha' => '2025-08-27',
                'tipo' => 'Ingreso',
                'descripcion' => 'Venta de 10 unidades del producto X',
                'monto' => 1250.50
                ],
                (object)['id' => 2,
                'fecha' => '2025-08-26',
                'tipo' => 'Egreso',
                'descripcion' => 'Pago de factura de servicios de internet y luz',
                'monto' => 450.00
                ],
            ],
            'proformas' => Contract::all(),
            'contratos' => [
                (object)['id' => 'C-2025-01',
                'nombre' => 'Contrato de Desarrollo Web',
                'cliente' => 'Cliente Ejemplo B',
                'fecha_inicio' => '2025-09-01',
                'estado' => 'Activo'
                ],
            ]
        ];

        $heads = [
            'comprobantes' => ['ID', 'Fecha', 'Tipo', 'Descripción', 'Monto (Bs)', 'Acciones'],
            'proformas' => ['Codigo.', 'Cliente', 'Fecha Emisión', 'Estado', 'Acciones'],
            'contratos' => ['Codigo.', 'Nombre Contrato', 'Cliente', 'Fecha Inicio', 'Estado', 'Acciones']
        ];


        return view('dashboard.voucher-view', compact('heads', 'config', 'data'));
    }

    public function diaryBook(){
        $heads = ['Fecha','Ingreso','Egreso','Descripción', 'Contrato', 'Cuenta'];
        return view('dashboard.diary-book',compact(['heads']));
    }

    public function settings(){
        $data = [
            'categories' => Category::all(),
            'accounts' => Account::all(),
            'users' => User::all()
        ];
        return view('dashboard.settings',compact(['data']));
    }
}
