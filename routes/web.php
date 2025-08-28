<?php

use App\Http\Controllers\DashboardController;
use App\Livewire\AssemblyForm;
use App\Livewire\PartnerForm;
use App\Livewire\ProductForm;
use App\Livewire\SupplierForm;
use App\Livewire\ClientForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect(route('login'));
});

Auth::routes();


Route::middleware('auth')->prefix('dashboard')->group(function(){
    Route::controller(DashboardController::class)->group(function(){
        Route::get('/','index')->name('dashboard.index');
        Route::get('supplier','supplier')->name('dashboard.supplier');
        Route::get('product','product')->name('dashboard.product');
        Route::get('assembly','assembly')->name('dashboard.assembly');
        Route::get('partner','partner')->name('dashboard.partner');
    });
    Route::get('supplier/{id}',SupplierForm::class)->name('dashboard.supplier.form');
    Route::get('product/{id}',ProductForm::class)->name('dashboard.product.form');
    Route::get('assembly/{code}',AssemblyForm::class)->name('dashboard.assembly.form');
    Route::get('partner/{id}',PartnerForm::class)->name('dashboard.partner.form');


    Route::get('client','client')->name('dashboard.client');

    /* Ejemplo para datos en la pestaña de comprobante xD */

    Route::get('comprobante/design', function () {
        $config = ['columns' => [null, null, null, null, null, ['orderable' => false, 'searchable' => false]]];


        $data = [
            'comprobantes' => [
                (object)['id' => 1, 'fecha' => '2025-08-27', 'tipo' => 'Ingreso', 'descripcion' => 'Venta de 10 unidades del producto X', 'monto' => 1250.50],
                (object)['id' => 2, 'fecha' => '2025-08-26', 'tipo' => 'Egreso', 'descripcion' => 'Pago de factura de servicios de internet y luz', 'monto' => 450.00],
            ],
            'proformas' => [
                (object)['id' => 'PF-001', 'cliente' => 'Cliente Ejemplo A', 'fecha' => '2025-08-20', 'estado' => 'Enviada', 'total' => 3500.00],
            ],
            'contratos' => [
                (object)['id' => 'C-2025-01', 'nombre' => 'Contrato de Desarrollo Web', 'cliente' => 'Cliente Ejemplo B', 'fecha_inicio' => '2025-09-01', 'estado' => 'Activo'],
            ]
        ];

        $heads = [
            'comprobantes' => ['ID', 'Fecha', 'Tipo', 'Descripción', 'Monto (Bs)', 'Acciones'],
            'proformas' => ['Cód.', 'Cliente', 'Fecha Emisión', 'Estado', 'Total (Bs)', 'Acciones'],
            'contratos' => ['Cód.', 'Nombre Contrato', 'Cliente', 'Fecha Inicio', 'Estado', 'Acciones']
        ];


        return view('dashboard.voucher-view', compact('heads', 'config', 'data'));
    })->name('dashboard.comprobante.design');


    Route::get('comprobante/form/design', function () {

        return view('livewire.voucher-form-design');
    })->name('dashboard.comprobante.form.design');


    Route::get('proforma/form/design', function () {

        return view('livewire.voucher-prof-form');
    })->name('dashboard.proforma.form.design');


    Route::get('contrato/form/design', function () {

        return view('livewire.voucher-cont-form');
    })->name('dashboard.contrato.form.design');

    Route::get('client/{id?}',ClientForm::class)->name('dashboard.client.form'); // Añadido '?' por si acaso
});
