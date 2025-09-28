<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Delivery;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class PdfController extends Controller
{
    public function generateVoucher($id){
        if(!Gate::allows('voucher-permission',3))
            abort('404');
        $data = Transaction::find($id);
        $pdf = Pdf::setOptions([
            'isHtmlParseEnabled' => true,
            'isRemoteEnabled' => true
        ])->loadView('pdf.voucher',[
            'logo' => 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('img/logo.png'))),
            'number' => $data->id,
            'ci' => $data->contract_partner?->partner->person->ci ?? (Auth::user()?->person->ci ?? Auth::user()->id),
            'name' => $data->contract_partner?->partner->person->name ?? (Auth::user()?->person->name ?? Auth::user()->email),
            'account' => $data->account->name,
            'amount' => $data->amount,
            'type' => $data->type == 1 ? 'Ingreso' : 'Egreso',
            'date' => Carbon::parse($data->date)->format('d/m/Y'),
            'description' => $data->description,
            'contract' => $data->contract?->cod ?? '',
            'logo' => 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('img/logo.png')))
        ]);
        $pdf->setPaper('letter');
        $pdf->render();
        return $pdf->stream();
    }
    public function generateDelivery($id){
        if(!Gate::allows('delivery-permission',3))
            abort('404');
        $data = Delivery::find($id);
        $pdf = Pdf::setOptions([
            'isHtmlParseEnabled' => true,
            'isRemoteEnabled' => true
        ])->loadView('pdf.delivery',[
            'logo' => 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('img/logo.png'))),
            'id' => $data->id,
            'contract' => $data->contract->cod,
            'date' => $data->date,
            'received' => $data->received_by,
            'name' => $data->contract->client->organization ?? $data->contract->client->person->name,
            'nit' => $data->contract->client->nit ?? $data->contract->client->person->ci,
            'data' => $data->detail_contract,
        ]);
        $pdf->setPaper('letter');
        $pdf->render();
        return $pdf->stream();
    }

    public function generateProof($id){
        $pdf = Pdf::setOptions([
            'isHtmlParseEnabled' => true,
            'isRemoteEnabled' => true
        ])->loadView('pdf.proof',[
            'contract' => Contract::find($id),
            'logo' => 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('img/logo.png')))
        ]);
        $pdf->setPaper('letter');
        $pdf->render();
        return $pdf->stream();

    }
}
