<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante {{$number}}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        .header-table {
            width: 100%;
            border-bottom: 2px solid #000;
            margin-bottom: 20px;
        }

        .header-table td {
            vertical-align: top;
        }

        .header-logo {
            width: 220px;
        }

        .header-title {
            padding-top: 40px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }

        .header-info {
            width: 220px;
            text-align: right;
            font-size: 12px;
        }

        .details {
            margin-bottom: 20px;
        }

        .details h3 {
            margin: 5px 0;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .details-table th,
        .details-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .signatures-table {
            width: 100%;
            margin-top: 50px;
            text-align: center;
        }

        .signature-cell {
            width: 33%;
        }

        .signature-line {
            width: 280px;
            border-top: 1px solid #000;
            margin-top: 40px;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td class="header-logo">
                <img src="{{  $logo }}" alt="Logo" style="width:150px;border-radius: 5px;">
            </td>
            <td class="header-title">
                COMPROBANTE
            </td>
            <td class="header-info">
                <p><strong>N° Transacción:</strong> {{ $number ?? '' }}</p>
                <p><strong>Fecha:</strong> {{ $date ?? '' }}</p>
                <p><strong>Fecha Emisión:</strong> {{ now()->format('d/m/Y H:i') }}</p>
            </td>
        </tr>
    </table>

    <style>
        .details {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .details td {
            border: 1px solid #000;
            padding: 6px 10px;
            width: 50%;
            /* dos columnas */
            vertical-align: top;
        }

        .details strong {
            display: inline-block;
            width: 100px;
            /* ancho fijo para alinear etiquetas */
        }
    </style>

    <table class="details">
        <tr>
            <td><strong>NIT/CI:</strong> {{ $ci ?? '' }}</td>
            <td><strong>Nombre:</strong> {{ $name ?? '' }}</td>
        </tr>
        <tr>
            <td><strong>Tipo:</strong> {{ $type ?? '' }}</td>
            <td><strong>Cuenta:</strong> {{ $account ?? '' }}</td>
        </tr>
        <tr>
            <td><strong>Contrato:</strong> {{ $contract ?? '' }}</td>
            <td><strong>Cantidad:</strong> {{ Illuminate\Support\Number::format($amount,precision:2) }} Bs</td>
        </tr>
    </table>

    <!-- DETALLE EXTRA -->
    <table class="details-table">
        <th style="font-size: 18px;text-align: center;">Descripción</th>
        <tr>
            <td>{{ $description ?? '---' }}</td>
        </tr>
    </table>

    <!-- FIRMAS -->
    <table class="signatures-table">
        <tr>
            <td class="signature-cell">
                <div class="signature-line">Firma Beneciado</div>
            </td>
            <td class="signature-cell">
                <div class="signature-line">Firma Autorizada</div>
            </td>
        </tr>
    </table>
</body>
</html>
