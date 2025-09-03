<?php

use App\Http\Controllers\DashboardController;
use App\Livewire\AssemblyForm;
use App\Livewire\CategoryForm;
use App\Livewire\PartnerForm;
use App\Livewire\ProductForm;
use App\Livewire\SupplierForm;
use App\Livewire\ClientForm;
use App\Livewire\ContractForm;
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

        Route::get('client','client')->name('dashboard.client');

        Route::get('proof', 'proof')->name('dashboard.proof');

        Route::get('settings','settings')->name('dashboard.settings');
        Route::get('diary-book','diaryBook')->name('dashboard.diary_book');
    });
    Route::get('supplier/{id}',SupplierForm::class)->name('dashboard.supplier.form');
    Route::get('product/{id}',ProductForm::class)->name('dashboard.product.form');
    Route::get('assembly/{code}',AssemblyForm::class)->name('dashboard.assembly.form');
    Route::get('partner/{id}',PartnerForm::class)->name('dashboard.partner.form');
    Route::get('client/{id}',ClientForm::class)->name('dashboard.client.form'); // Añadido '?' por si acaso
    Route::get('proof/{id}',ContractForm::class)->name('dashboard.proof.form');
    Route::get('settings/category/{id}',CategoryForm::class)->name('dashboard.settings.category');





    /* Ejemplo para datos en la pestaña de comprobante xD */



    Route::get('comprobante/form/design', function () {

        return view('livewire.voucher-form-design');
    })->name('dashboard.comprobante.form.design');


    Route::get('proforma/form/design', function () {

        return view('livewire.voucher-prof-form');
    })->name('dashboard.proforma.form.design');


    Route::get('contrato/form/design', function () {

        return view('livewire.voucher-cont-form');
    })->name('dashboard.contrato.form.design');

});
