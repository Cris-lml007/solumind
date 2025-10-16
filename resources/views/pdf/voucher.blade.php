@extends('pdf.t')

@section('type')
    COMPROBANTE <div style="display: inline;color: #D8A300;">#{{ $number ?? '' }}</div>
@endsection

@section('extra')
    <p><strong>Fecha:</strong> {{ $date ?? '' }}</p>
@endsection

@section('info')
    <div style="width: 350px;">
        <h4>ID/CI</h4>
        <p style="margin-bottom: 5px;">{{ $ci ?? '' }}</p>
        <h4>Tipo</h4>
        <p style="margin-bottom: 5px;">{{ $type ?? '' }}</p>
        <h4>Contrato</h4>
        <p style="margin-bottom: 5px;">{{ ($contract ?? '') . ' (' . $client . ')' }}</p>
    </div>
    <div>
        <h4>Nombre</h4>
        <p style="margin-bottom: 5px;">{{ $name ?? '' }}</p>
        <h4>A Cuenta</h4>
        <p style="margin-bottom: 5px;height: 14px;width: 250px;">{{ $account ?? '' }}</p>
        <h4>Importe</h4>
        <p>{{ Illuminate\Support\Number::format($amount, precision: 2) }} Bs</p>
    </div>
@endsection

@section('content')
    <!-- DETALLE EXTRA -->
    <div style="min-height: 380px;">
        <table style="min-height: 370px;">
            <thead>
                <th style="font-size: 18px;text-align: center;">Descripción</th>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $description ?? '---' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
