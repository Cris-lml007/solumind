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
use App\Models\DetailContract;
use App\Models\Person;
use App\Models\Transaction;
use App\Models\User;
use App\StatusContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    public function index(){
        $data = Transaction::where('date',Carbon::now()->toDateString())->get();
        $total = DetailContract::sum(DB::raw('sale_price*quantity'));
        $utotal = DetailContract::sum(DB::raw('sale_price*quantity - purchase_total'));
        $patrimony = Transaction::where('type',1)->sum('amount') - Transaction::where('type',2)->sum('amount');
        return view('dashboard.index',compact(['data','utotal','total','patrimony']));
    }
    public function supplier(){
        if(!Gate::allows('supplier-read'))
            abort('404');
        $heads = ['ID', 'NIT', 'Nombre Proveedor', 'Contacto Principal', 'Acciones'];
        $config = ['columns' => [null, null, null, null, ['orderable' => false, 'searchable' => false]]];
        $data = Supplier::all();
        return view('dashboard.supplier-view', compact(['heads', 'config','data']));
    }

    public function product(){
        if(!Gate::allows('product-read'))
            abort('404');
        $heads = ['ID', 'Nombre Producto', 'Proveedor', 'Precio (Bs)', 'Acciones'];
        $config = ['columns' => [null, null, null, null, ['orderable' => false, 'searchable' => false]]];
        $data = Product::all();
        return view('dashboard.product-view', compact(['heads', 'config','data']));
    }

    public function assembly(){
        if(!Gate::allows('item-read'))
            abort('404');
        $heads = ['ID','Codigo', 'Nombre de Producto', 'Precio', 'Acciones'];
        $config = ['columns' => [null, null, null, null, ['orderable' => false, 'searchable' => false]]];
        $data = Item::all();
        return view('dashboard.assembly-view',compact(['heads','config','data']));
    }

    public function partner(){
        if(!Gate::allows('partner-read'))
            abort('404');
        $heads = ['ID','CI','Nombre Completo','Contacto Principal','Acciones'];
        $config = ['columns' => [null, null, null, ['orderable' => false, 'searchable' => false]]];
        $data = Partner::all();
        return view('dashboard.partner-view',compact(['heads','config','data']));
    }

    public function client(){
        if(!Gate::allows('client-read'))
            abort('404');
        $heads = ['ID', 'CI', 'NIT', 'Nombre Cliente', 'Contacto Principal', 'Acciones'];
        $config = ['columns' => [null,null, null, null, null, null, ['orderable' => false, 'searchable' => false]]];
        $data = Client::all();
        return view('dashboard.client-view', compact(['heads', 'config','data']));
    }

    public function proof(){
        if(!Gate::allows('voucher-read'))
            abort('404');
        $config = ['columns' => [null, null, null, null, null, ['orderable' => false, 'searchable' => false]]];
        $data = [
            'comprobantes' => Transaction::all(),
            'proformas' => Contract::Where('status','<',StatusContract::CONTRACT->value)->get(),
            'contratos' => Contract::where('status','>=',StatusContract::CONTRACT->value)->get()
        ];

        $heads = [
            'comprobantes' => ['ID', 'Fecha', 'Tipo', 'Descripción', 'Monto (Bs)', 'Acciones'],
            'proformas' => ['ID', 'Codigo', 'Cliente', 'Fecha Emisión', 'Estado', 'Acciones'],
            'contratos' => ['ID', 'Codigo', 'Cliente', 'Plazo de Entrega', 'Estado', 'Acciones']
        ];


        return view('dashboard.voucher-view', compact('heads', 'config', 'data'));
    }

    public function diaryBook(){
        if(!Gate::allows('transaction-read'))
            abort('404');
        $heads = ['ID', 'Fecha','Ingreso (Bs)','Egreso (Bs)','Descripción', 'Contrato', 'Cuenta'];
        $data = Transaction::orderBy('date','asc')->get();
        return view('dashboard.diary-book',compact(['heads','data']));
    }

    public function ledger(){
        if(!Gate::allows('ledger-read'))
            abort('404');
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
        if(!Gate::allows('delivery-read'))
            abort('404');
        $heads = ['Fecha','ID','Codigo de Contrato','Importe (Bs)','Saldo (Bs)','Generar'];
        $data = Delivery::all();
        return view('dashboard.delivery-view',compact(['heads','data']));
    }

    public function report(){
        $contracts = DB::table('contracts')->groupBy('status')->select(DB::raw('status,COUNT(*) as count'))->get()->toArray();

        $transactions = [Transaction::where('type',1)->sum('amount'),Transaction::where('type',2)->sum('amount')];

        $accounts = DB::table('transactions')
            ->join('accounts', 'accounts.id', '=', 'transactions.account_id')
            ->select(
                'accounts.name',
                DB::raw('SUM(CASE WHEN transactions.type = 1 THEN amount
                WHEN transactions.type = 2 THEN -amount
                ELSE 0 END) as amount')
            )
            ->groupBy('accounts.id', 'accounts.name')
            ->get()
            ->toArray();

        $products = DB::table('detail_contracts')
            ->groupBy('detailable_id','detailable_type')->join('products','products.id','=','detail_contracts.detailable_id')
            ->select(DB::raw('products.cod, SUM(quantity) as quantity'))
            ->get()->toArray();
        $items = DB::table('detail_contracts')
            ->groupBy('detailable_id','detailable_type')->join('items','items.id','=','detail_contracts.detailable_id')
            ->select(DB::raw('items.cod, SUM(quantity) as quantity'))
            ->get()->toArray();


        $products = array_merge($products,$items);

        // $deliveries = DB::table('deliveries')
        //     ->select(
        //         DB::raw('YEAR(created_at) as year'),
        //         DB::raw('MONTH(created_at) as month'),
        //         DB::raw('COUNT(*) as total_deliveries')
        //     )
        //     ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
        //     ->orderBy(DB::raw('YEAR(created_at)'), 'desc')
        //     ->orderBy(DB::raw('MONTH(created_at)'), 'desc')
        //     ->get()->toArray();
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            $deliveries = DB::table('deliveries')
                ->select(
                    DB::raw("DATE_FORMAT(created_at, '%Y-%m') as period"),
                    DB::raw("COUNT(*) as total_deliveries")
                )
                ->groupBy('period')
                ->orderBy('period', 'desc')
                ->get()->toArray();
        } elseif ($driver === 'sqlite') {
            $deliveries = DB::table('deliveries')
                ->select(
                    DB::raw("strftime('%Y-%m', created_at) as period"),
                    DB::raw("COUNT(*) as total_deliveries")
                )
                ->groupBy('period')
                ->orderBy('period', 'desc')
                ->get()->toArray();
        }
        // dd($deliveries);
        return view('dashboard.report-view',compact('transactions','accounts','contracts','products','deliveries'));
    }

    public function settings(){
        if(!Gate::allows('config-read'))
            abort('404');
        $data = [
            'categories' => Category::all(),
            'accounts' => Account::all(),
            'users' => User::all(),
            'persons' => Person::all()
        ];
        return view('dashboard.settings',compact(['data']));
    }

    public function support(){
        return view('dashboard.support-view');
    }
}
