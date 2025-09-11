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
use App\Models\Delivery;
use App\Models\Transaction;
use App\Models\User;
use App\StatusContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'comprobantes' => Transaction::all(),
            'proformas' => Contract::Where('status','<',StatusContract::CONTRACT->value)->get(),
            'contratos' => Contract::where('status','>=',StatusContract::CONTRACT->value)->get()
        ];

        $heads = [
            'comprobantes' => ['ID', 'Fecha', 'Tipo', 'Descripción', 'Monto (Bs)', 'Acciones'],
            'proformas' => ['Codigo.', 'Cliente', 'Fecha Emisión', 'Estado', 'Acciones'],
            'contratos' => ['Codigo.', 'Cliente', 'Plazo de Entrega', 'Estado', 'Acciones']
        ];


        return view('dashboard.voucher-view', compact('heads', 'config', 'data'));
    }

    public function diaryBook(){
        $heads = ['Fecha','Ingreso (Bs)','Egreso (Bs)','Descripción', 'Contrato', 'Cuenta'];
        $data = Transaction::orderBy('date','asc')->get();
        return view('dashboard.diary-book',compact(['heads','data']));
    }

    public function ledger(){
        $heads = ['Fecha','Ingreso (Bs)','Egreso (Bs)','Descripción', 'Contrato', 'Cuenta'];
        $data = Transaction::orderBy('date','asc')->get();
        $data1 = DB::table('transactions')
            ->join('accounts','accounts.id','=','transactions.account_id')
            ->select(
                'account_id',
                'accounts.name',
                DB::raw("SUM(CASE WHEN transactions.type = 1 THEN amount ELSE 0 END) -
                SUM(CASE WHEN transactions.type = 2 THEN amount ELSE 0 END) as balance")
            )
            ->groupBy('account_id', 'accounts.name')
            ->get();
        // dd($data1[0]);
        return view('dashboard.ledger-view',compact(['heads','data','data1']));
    }

    public function delivery(){
        $heads = ['Fecha','ID','Codigo de Contrato','Importe (Bs)','Saldo (Bs)','Generar'];
        $data = Delivery::all();
        return view('dashboard.delivery-view',compact(['heads','data']));
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
