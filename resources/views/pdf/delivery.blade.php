@extends('pdf.t')

@section('type')
    NOTA DE REMISIÓN <div style="display: inline;color: #D8A300;">#{{ $id }}</div>
@endsection

@section('info')
    <div style="width: 350px;">
        <h4>Fecha</h4>
        <p style="margin-bottom: 5px;">{{ $date ?? '' }}</p>
        <h4>NIT</h4>
        <p style="margin-bottom: 5px;">{{ $nit ?? '' }}</p>
        <h4>Recibido por</h4>
        <p style="margin-bottom: 5px;">{{ $received ?? '' }}</p>
    </div>
    <div>
        <h4>Contrato</h4>
        <p style="margin-bottom: 5px;">{{ $contract ?? '' }}</p>
        <h4>Cliente</h4>
        <p style="margin-bottom: 5px;height: 14px;width: 250px;">{{ $name }}</p>
        <h4>Autorizado por</h4>
        <p>{{ Auth::user()->person->name ?? Auth::user()->email }}</p>
    </div>
@endsection

@section('content')
    <div style="min-height: 380px;">
        <h3>Productos Entregados</h3>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad Entregado</th>
                    <th>Unidad</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td>{{ $item->detailable->name . ' ' . ($item->detailable->size ?? '') }}</td>
                        <td>{{ $item->pivot->quantity }}</td>
                        <td>{{ $item->detailable->unit }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
