<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function generateVoucher($id){
        $data = Transaction::find($id);
        $pdf = Pdf::setOptions([
            'isHtmlParseEnabled' => true,
            'isRemoteEnabled' => true
        ])->loadView('pdf.voucher',[
            'number' => $data->id,
            'ci' => $data->contract_partner->partner->person->ci,
            'name' => $data->contract_partner->partner->person->name,
            'account' => $data->account->name,
            'amount' => $data->amount,
            'type' => $data->type == 1 ? 'Ingreso' : 'Egreso',
            'date' => Carbon::parse($data->date)->format('d/m/Y H:i'),
            'description' => $data->description,
            'contract' => $data->contract->cod
        ]);
        $pdf->setPaper('letter');
        $pdf->render();
        return $pdf->stream();
    }
}
