<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Barryvdh\DomPDF\Facade\Pdf;
use NumberToWords\NumberToWords;

class ContractController extends Controller
{
    public function generarPDF(Contract $contract)
    {
 
        $contract->load('client.person', 'detail_contract.detailable');
        $total = $contract->detail_contract->sum(function ($item) {
            return $item->sale_price * $item->quantity;
        });

        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('es');

        $parteEntera = intval($total);
        $centavos = round(($total - $parteEntera) * 100);
        $totalEnPalabras = ucfirst($numberTransformer->toWords($parteEntera)) . ' ' . str_pad($centavos, 2, '0', STR_PAD_LEFT) . '/100 Bolivianos';
        $pdf = PDF::loadView('proformas.pdf.template', compact('contract', 'totalEnPalabras'));

        $pdf->setPaper('letter', 'portrait');

        return $pdf->download('Proforma-' . $contract->code . '.pdf');
    }
}