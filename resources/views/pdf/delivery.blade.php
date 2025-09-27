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
                <img src="{{ $logo }}" alt="{{ $logo }}" style="max-width: 150px; max-height: 80px;margin-top: 10px;border-radius: 5px;">
            </div>
            <div style="text-align: right;display: inline-block;width: 48%;height: 26px;">
                <strong>N° {{ $id }}</strong>
            </div>
        </div>

        <!-- Título -->
        <div style="flex: 1; text-align: center;">
            <h2>Nota de Remisión</h2>
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
                <th>Cantidad Entregado (Ud.)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
            <tr>
                <td>{{$item->detailable->name . ' ' . ($item->detailable->size ?? '')}}</td>
                <td>{{$item->pivot->quantity}}</td>
            </tr>
            @endforeach
        </tbody>
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
