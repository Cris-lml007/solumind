<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::controller(DashboardController::class)->prefix('/dashboard')->group(function(){
    Route::get('/','index')->name('dashboard.index');
    Route::get('supplier','supplier')->name('dashboard.supplier');
});
