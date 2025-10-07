<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Proforma</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        .bordered td,
        .bordered th {
            border: 1px solid #000;
            padding: 6px;
        }

        .header-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .mt-20 {
            margin-top: 20px;
        }

        .logo-box {
            border: 1px dashed #999;
            padding: 6px;
            width: 100%;
            height: 90px;
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }

        .logo-box img {
            max-width: 160px;
            max-height: 80px;
            display: block;
            margin: 0 auto;
        }

        .small {
            font-size: 11px;
            color: #555;
        }
    </style>
</head>
<body>
    <!-- Encabezado: logo e información -->
    <table>
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <div class="">
                    <img src="{{ $logo }}"
                        alt="LOGO" width="150px" style="margin-left: 10px;border-radius: 5px;">
                </div>
                <div style="vertical-align: top; padding-left: 12px;">
                    <!-- <strong>SOLUMIND</strong><br> -->
                    Dirección: Urb. Dios es Amor, M-c16- 10<br>
                    Telf. 25265721 Cel. 70415397 - 73819319<br>
                    solumind,or @gmail.com<br>
                </div>
            </td>


            <td style="width: 50%; vertical-align: middle;" class="right">
                <strong>Codigo:</strong> {{ $contract->cod }}<br>
                <strong>Fecha:</strong> {{ Carbon\Carbon::parse($contract->created_at)->toFormattedDateString() }}<br>
                <strong>Validez:</strong> {{ $contract->time_valide }} días
            </td>
        </tr>
        <!-- Fila para el título centrado -->
        <tr>
            <td colspan="3" class="header-title">PROFORMA</td>
        </tr>
    </table>

    <!-- Datos del cliente -->
    <table class="mt-20">
        <tr>
            <td style="width: 50%;border: solid 1px black;">
                <strong>CI/NIT:</strong> {{ $contract->client->nit ?? $contract->client->person->ci }}
            </td>
            <td style="width: 50%;border: solid 1px black;">
                <strong>Cliente:</strong> {{ $contract->client->organization ?? $contract->client->person->name }}<br>
            </td>
        </tr>
        <tr>
            <td style="width: 50%;border: solid 1px black;">
                <strong>Contacto:</strong>
                {{ !empty($contract->client->phone) ? $contract->client->phone : $contract->client->person->phone }}<br>
            </td>
            <td style="width: 50%;border: solid 1px black;">
                <strong>Correo:</strong>
                {{ !empty($contract->client->email) ? $contract->client->email : $contract->client->person->email }}
            </td>
        </tr>
        <tr>
            <td style="width: 50%;border: solid 1px black;">
                <strong>Condiciones de Pago:</strong> {{ $contract->payment }}
            </td>
            <td style="width: 50%;border: solid 1px black;">
                <strong>Plazo de Entrega:</strong> {{ Carbon\Carbon::parse($contract->time_delivery)->toFormattedDateString() }}
            </td>
        </tr>
    </table>

    <!-- Detalle de productos -->
    <table class="bordered mt-20">
        <thead>
            <tr>
                <th class="center" style="width: 5%;">#</th>
                <th class="center" style="width: 50%;">Descripción</th>
                <th class="center" style="width: 15%;">Cantidad (Ud.)</th>
                <th class="center" style="width: 15%;">Precio Unit.</th>
                <th class="center" style="width: 15%;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total = 0;
            @endphp
            @foreach ($contract->detail_contract as $index => $item)
                @php
                    $total += $item->sale_price * $item->quantity;
                @endphp
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>{{ $item->detailable->name . ' ' . $item->detailable->size }} -
                        {{ $item->detailable->description }}<br><strong>{{!empty($item->description) ? 'Observaciones' : ''}}</strong> @if(!empty($item->description)) <br>{{$item->description}} @endif</td>
                    <td class="center">{{ $item->quantity }}</td>
                    <td class="right">{{ Illuminate\Support\Number::format($item->sale_price, precision: 2) }}</td>
                    <td class="right">
                        {{ Illuminate\Support\Number::format($item->sale_price * $item->quantity, precision: 2) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2" class="right"><strong>Total</strong></td>
                <td colspan="3" class="right"><strong>{{ Illuminate\Support\Number::format($total, precision: 2) }} Bs</strong></td>
            </tr>
            <tr>
                <td colspan="5"><strong>{{ Str::upper($formater->format($total))}} BOLIVIANOS</strong></td>
            </tr>
            <tr>
                <td colspan="5" style="height:40px;">
                    <strong>Observaciones:</strong><br>
                    {{$contract->description}}
                </td>
            </tr>
        </tbody>
    </table>
    <!-- Firma -->
    <table class="mt-20" style="margin-top: 140px;">
        <tr>
            <td class="center" style="width: 50%;"> -----------------------------<br> Firma Cliente </td>
            <td class="center" style="width: 50%;"> -----------------------------<br> Firma Autorizada </td>
        </tr>
    </table>
</body>
</html>
