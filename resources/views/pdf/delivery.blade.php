<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de Entrega</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .header,
        .footer {
            text-align: center;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th,
        td {
            padding: 5px;
            text-align: left;
        }

        .no-border td {
            border: none;
        }

        .signatures td {
            padding-top: 40px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header" style="display: flex; align-items: center; justify-content: center;">
        <div>
            <!-- Logo -->
            <div style="text-align: left;display: inline-block;width: 48% ;">
                <img src="ruta/al/logo.png" alt="Logo Empresa" style="max-width: 100px; max-height: 80px;">
            </div>
            <div style="text-align: right;display: inline-block;width: 48%;">
                <strong>N° {{ $id }}</strong>
            </div>
        </div>

        <!-- Título -->
        <div style="flex: 1; text-align: center;">
            <h2>Detalle de Entrega</h2>
        </div>
    </div>

    <table class="no-border">
        <tr>
            <td><strong>Fecha:</strong> {{$date ?? ''}}</td>
            <td><strong>Contrato:</strong> {{$contract ?? ''}}</td>
        </tr>
        <tr>
            <td><strong>NIT:</strong> {{$nit}}</td>
            <td><strong>Cliente:</strong> {{$name}}</td>
        </tr>
        <tr>
            <td><strong>Recibido por:</strong> {{$received ?? ''}}</td>
            <td><strong>Autorizado por:</strong> {{Auth::user()->person->name ?? Auth::user()->email}}</td>
        </tr>
    </table>

    <h3>Productos Entregados</h3>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <td>Subtotal</td>
            </tr>
        </thead>
        <tbody>
            @php
                $total = 0;
            @endphp
            @foreach ($data as $item)
            @php
                $total += $item->pivot->quantity*$item->detailable->price;
            @endphp
            <tr>
                <td>{{$item->detailable->name ?? ''}}</td>
                <td>{{$item->pivot->quantity}}</td>
                <td>{{ Number::format($item->detailable->price,precision: 2) }}</td>
                <td>{{ Number::format($item->pivot->quantity*$item->detailable->price, precision: 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <th colspan="3">TOTAL</th>
            <th>{{ Number::format($total, precision: 2) }} Bs</th>
        </tfoot>
    </table>

    <br><br>
    <table class="signatures no-border">
        <tr>
            <td>_________________________<br>Recibido por</td>
            <td>_________________________<br>Autorizado por</td>
        </tr>
    </table>
</body>
</html>
