<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PdfController;
use App\Livewire\AccountForm;
use App\Livewire\AssemblyForm;
use App\Livewire\AssemblyView;
use App\Livewire\CategoryForm;
use App\Livewire\PartnerForm;
use App\Livewire\ProductForm;
use App\Livewire\SupplierForm;
use App\Livewire\ClientForm;
use App\Livewire\ContractForm;
use App\Livewire\DeliveryForm;
use App\Livewire\DeliveryView;
use App\Livewire\DiaryBookForm;
use App\Livewire\History;
use App\Livewire\PartnerView;
use App\Livewire\PersonForm;
use App\Livewire\ProductView;
use App\Livewire\SupplierView;
use App\Livewire\Userform;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect(route('login'));
});

Auth::routes();


Route::middleware('auth')->prefix('dashboard')->group(function(){
    Route::controller(DashboardController::class)->group(function(){
        Route::get('/','index')->name('dashboard.index');
        // Route::get('supplier','supplier')->name('dashboard.supplier');
        // Route::get('product','product')->name('dashboard.product');
        //Route::get('assembly','assembly')->name('dashboard.assembly');
        // Route::get('partner','partner')->name('dashboard.partner');
        Route::get('ledger','ledger')->name('dashboard.ledger');
        // Route::get('delivery','delivery')->name('dashboard.delivery');

        Route::get('delivery/delete/{delivery?}','removeDelivery')->name('dashboard.delivery.remove');

        Route::get('client','client')->name('dashboard.client');

        Route::get('proof', 'proof')->name('dashboard.proof');

        Route::get('settings','settings')->name('dashboard.settings');
        Route::get('diary-book','diaryBook')->name('dashboard.diary_book');
        Route::get('report','report')->name('dashboard.report');
        // Route::get('support','support')->name('dashboard.support');
    });

    Route::get('assembly',AssemblyView::class)->name('dashboard.assembly');
    Route::get('product',ProductView::class)->name('dashboard.product');
    Route::get('supplier',SupplierView::class)->name('dashboard.supplier');
    Route::get('partner',PartnerView::class)->name('dashboard.partner');
    Route::get('delivery',DeliveryView::class)->name('dashboard.delivery');




    Route::get('supplier/{id}',SupplierForm::class)->name('dashboard.supplier.form');
    Route::get('product/{id}',ProductForm::class)->name('dashboard.product.form');
    Route::get('assembly/{code}',AssemblyForm::class)->name('dashboard.assembly.form');
    Route::get('partner/{id}',PartnerForm::class)->name('dashboard.partner.form');
    Route::get('client/{id}',ClientForm::class)->name('dashboard.client.form');
    Route::get('proof/{id}',ContractForm::class)->name('dashboard.proof.form');
    Route::get('diary-book/{id}',DiaryBookForm::class)->name('dashboard.diary_book.form');
    Route::get('settings/category/{id}',CategoryForm::class)->name('dashboard.settings.category');
    Route::get('settings/account/{id}',AccountForm::class)->name('dashboard.settings.account');
    Route::get('settings/user/{id}',Userform::class)->name('dashboard.settings.user.form');
    Route::get('settings/person/{id}',PersonForm::class)->name('dashboard.settings.person.form');
    Route::get('history',History::class)->name('dashboard.history');
    Route::get('delivery/{id}',DeliveryForm::class)->name('dashboard.delivery.form');

    Route::get('proof/pdf/{id}',[PdfController::class,'generateVoucher'])->name('dashboard.proof.pdf');
    Route::get('delivery/pdf/{id}',[PdfController::class,'generateDelivery'])->name('dashboard.delivery.pdf');

    Route::get('profile',function(){
        $id = Auth::user()->id;
        return redirect(route('dashboard.settings.user.form',$id));
    })->name('dashboard.profile');

    Route::get('proof/{id}/pdf',[PdfController::class,'generateProof'])->name('dashboard.proof.form.pdf');

    // Route::get('pdf',function(){
    //     $logo = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('img/logo.png')));
    //     return view('pdf.t',compact(['logo']));
    //     $pdf = Pdf::setOptions([
    //         'isHtmlParseEnabled' => true,
    //         'isRemoteEnabled' => true
    //     ])->loadView('pdf.t');
    //     $pdf->setPaper('letter');
    //     $pdf->render();
    //     return $pdf->stream();
    // })->name('dashboard.pdf');





    /* Ejemplo para datos en la pestaña de comprobante xD */



    // Route::get('comprobante/form/design', function () {
    //
    //     return view('livewire.voucher-form-design');
    // })->name('dashboard.comprobante.form.design');
    //
    //
    // Route::get('proforma/form/design', function () {
    //
    //     return view('livewire.voucher-prof-form');
    // })->name('dashboard.proforma.form.design');
    //
    //
    // Route::get('contrato/form/design', function () {
    //
    //     return view('livewire.voucher-cont-form');
    // })->name('dashboard.contrato.form.design');

});
