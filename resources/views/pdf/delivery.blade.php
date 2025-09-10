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
        <!-- Logo -->
        <div style="flex: 0 0 100px; text-align: left;">
            <img src="ruta/al/logo.png" alt="Logo Empresa" style="max-width: 100px; max-height: 80px;">
        </div>

        <!-- Título -->
        <div style="flex: 1; text-align: center;">
            <h2>Detalle de Entrega</h2>
        </div>
    </div>

    <table class="no-border">
        <tr>
            <td><strong>Fecha:</strong> {{$date ?? ''}}</td>
            <td><strong>ID Retiro:</strong> {{$id ?? ''}}</td>
        </tr>
        <tr>
            <td><strong>Recibido por:</strong> {{$received ?? ''}}</td>
            <td><strong>Autorizado por:</strong> {{Auth::user()->person->name ?? Auth::user()->email}}</td>
        </tr>
        <tr>
            <td><strong>Cliente:</strong> {{$name}}</td>
            <td><strong>NIT:</strong> {{$nit}}</td>
        </tr>
    </table>

    <h3>Productos Entregados</h3>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
            <tr>
                <td>{{$item->detailable->name ?? ''}}</td>
                <td>{{$item->pivot->quantity}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Resumen de Pago</h3>
    <table>
        <tr>
            <td><strong>Saldo pendiente:</strong></td>
            <td>{{$balance}} Bs</td>
        </tr>
        <tr>
            <td><strong>Depósito:</strong></td>
            <td>{{$amount}} Bs</td>
        </tr>
        <tr>
            <td><strong>Saldo restante:</strong></td>
            <td>{{$balance - $amount}} Bs</td>
        </tr>
    </table>

    <br><br>
    <table class="signatures no-border">
        <tr>
            <td>_________________________<br>Recibido por</td>
            <td>_________________________<br>Autorizado por</td>
            <td>_________________________<br>Cliente</td>
        </tr>
    </table>
</body>
</html>
