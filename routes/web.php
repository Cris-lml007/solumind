<?php

use App\Http\Controllers\DashboardController;
use App\Livewire\AssemblyForm;
use App\Livewire\PartnerForm;
use App\Livewire\ProductForm;
use App\Livewire\SupplierForm;
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
});
