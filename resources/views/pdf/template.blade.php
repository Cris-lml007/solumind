<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Proforma #{{ $contract->code ?? '' }}</title>
    <style>
        @page {
            margin: 2cm 2cm 2cm 2cm;
        }
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 9px;
            color: #333;
        }


        .proforma-vertical {
            position: fixed;
            top: 70%;
            left: 0.8cm;
            z-index: -10;
            transform: rotate(-90deg);
            transform-origin: left top;
            font-size: 40px;
            font-weight: bold;
            color: #000;
            white-space: nowrap;
        }


        .main-content {
            position: relative;
            z-index: 2;
            background-color: white;
        }

        .header-table {
            width: 100%;
            border-bottom: 2px solid #e43a45;
            padding-bottom: 10px;
        }
        .header-table td {
            vertical-align: top;
        }
        .info-section {
            width: 100%;
            border-top: 2px solid #e43a45;
            padding-top: 10px;
            margin-bottom: 15px;
        }
        .info-table {
            width: 100%;
            font-size: 9px;
        }
        .info-table td {
            padding: 2px 3px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 9px;
        }
        .items-table th {
            background-color: #d6a133;
            color: #000;
            font-weight: bold;
            padding: 6px;
            border: 1px solid #555;
            text-align: center;
        }
        .items-table td {
            border: 1px solid #999;
            padding: 6px;
            vertical-align: top;
        }
        .footer-table {
            width: 100%;
            margin-top: 15px;
            border-top: 2px solid #d6a133;
            padding-top: 5px;
        }
        .footer-table td {
            font-size: 11px;
        }


        .page-footer {
            position: fixed;
            bottom: 2.5cm;
            left: 2cm;
            right: 2cm;
        }
        .footer-content-table {
            width: 100%;
        }
        .footer-content-table td {
            vertical-align: bottom;
        }
        .signature-image {
            width: 160px;
            height: auto;
        }


        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .w-60 { width: 60%; }
        .w-40 { width: 40%; }
        .logo { width: 180px; }
    </style>
</head>
<body>



    <div class="main-content">

        <table class="header-table">
            <tr>
                <td class="w-60">
                    Urb. Dios es Amor, M-c16 - 10<br>
                    Telf. 25265721 Cel. 70415397 - 73819319<br>
                    solumind.or@gmail.com
                </td>
                <td class="w-40 text-right">
                    <img src="{{ public_path('img/AdminLTELogo.png') }}" alt="Logo" class="logo">
                </td>
            </tr>
        </table>

        <div class="info-section">
            <table class="info-table">
                <tr>
                    <td class="font-bold" style="width: 15%;">Fecha:</td>
                    <td style="width: 35%;">{{ \Carbon\Carbon::parse($contract->created_at ?? '')->format('d \d\e F \d\e Y') }}</td>
                    <td class="font-bold" style="width: 15%;">Facturable a:</td>
                    <td style="width: 35%;">{{ $contract->client->person->name ?? '' }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td class="font-bold">Envío a:</td>
                    <td>{{ $contract->client->organization ?? '' }}</td>
                </tr>
            </table>
        </div>

        <table class="info-table">
            <tr>
                <td class="font-bold" style="width: 20%;">OBSERVACIONES:</td>
                <td>{{ $contract->description ?? '' }}</td>
            </tr>
            <tr>
                <td class="font-bold">VALIDEZ DE OFERTA:</td>
                <td>{{ $contract->time_valide ?? '' }}</td>
            </tr>
            <tr>
                <td class="font-bold">FORMA DE PAGO:</td>
                <td>{{ $contract->payment ?? ''}}</td>
            </tr>
             <tr>
                <td class="font-bold">TIEMPO DE ENTREGA:</td>
                <td>{{ $contract->time_delivery ?? ''}}</td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%;">ITEM</th>
                    <th style="width: 10%;">CANTIDAD</th>
                    <th style="width: 10%;">UNIDAD</th>
                    <th style="width: 50%;">DESCRIPCION</th>
                    <th style="width: 12.5%;">P. UNIT.</th>
                    <th style="width: 12.5%;">SUBTOTAL</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($contract->detail_contract ?? [] as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ number_format($item->quantity ?? 0, 0) }}</td>
                    <td class="text-center">{{ $item->detailable->unit ?? ''}}</td>
                    <td>
                        <p class="font-bold">{{ $item->detailable->name ?? ''}}</p>
                        <p>{!! nl2br(e($item->description ?? '')) !!}</p>
                    </td>
                    <td class="text-right">{{ number_format($item->sale_price ?? 0, 2, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($item->sale_price * $item->quantity, 2, ',', '.') }}</td>
                </tr>
                @php $total += $item->sale_price * $item->quantity; @endphp
                @endforeach
            </tbody>
        </table>

        <table class="footer-table">
            <tr>
                <td class="font-bold">Son: {{ $totalEnPalabras ?? '' }}</td>
                <td class="font-bold text-right" style="font-size: 14px;">{{ number_format($total, 2, ',', '.') }}</td>
            </tr>
        </table>

    </div>


    <div class="page-footer">
        <table class="footer-content-table">
            <tr>

                <td>

                    <img src="{{ public_path('img/firma_solumind.png') }}" alt="Firma" class="signature-image">
                </td>

                <td class="text-right">
                    Su consulta no molesta..................
                </td>
            </tr>
        </table>
    </div>


</body>
</html>
