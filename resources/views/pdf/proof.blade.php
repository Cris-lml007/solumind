@extends('pdf.t')

@section('type')
    @if ($contract->status->value < 3)
    PRO-FORMA <div style="display: inline; color: #D8A300;">#{{ $contract->id }}</div>
    @else
        CONTRATO <div style="display: inline; color: #D8A300;">{{ $contract->cod }}</div>
    @endif
@endsection

@section('extra')
    <strong>
        Validez:</strong> {{ $contract->time_valide }} días<br>
    <strong>
        Firma del Contrato:</strong> {{ $contract->time_valide }} días
@endsection

@section('info')
    <div style="width: 350px;">
        <h4>CI/NIT</h4>
        <p style="margin-bottom: 5px;">{{ $contract->client->nit ?? $contract->client->person->ci }}</p>
        <h4>Contacto</h4>
        <p style="margin-bottom: 5px;">
            {{ !empty($contract->client->phone) ? $contract->client->phone : $contract->client->person->phone }}</p>
        <h4>Condiciones de Pago</h4>
        <p style="margin-bottom: 5px;">{{ $contract->payment }}</p>
    </div>
    <div>
        <h4>Cliente</h4>
        <p style="margin-bottom: 5px;">{{ $contract->client->organization ?? $contract->client->person->name }}</p>
        <h4>Correo</h4>
        <p style="margin-bottom: 5px;height: 14px;">
            {{ !empty($contract->client->email) ? $contract->client->email : $contract->client->person->email }}</p>
        <h4>Plazo de Entrega</h4>
        <p>{{ Carbon\Carbon::parse($contract->time_delivery)->toFormattedDateString() }}</p>
    </div>
@endsection

@section('content')
    <!-- Detalle de productos -->
    <table class="bordered mt-20" style="min-height: 380px;">
        <thead>
            <tr>
                <th class="center" style="width: 5%;">#</th>
                <th class="center" style="width: 50%;">Descripción</th>
                <th class="center">Unidad</th>
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
                        {{ $item->detailable->description }}<br><strong>{{ !empty($item->description) ? 'Observaciones' : '' }}</strong>
                        @if (!empty($item->description))
                            <br>{{ $item->description }}
                        @endif
                    </td>
                    <td>{{ $item->detailable->unit }}</td>
                    <td class="center">{{ $item->quantity }}</td>
                    <td class="right">{{ Illuminate\Support\Number::format($item->sale_price, precision: 2) }}</td>
                    <td class="right">
                        {{ Illuminate\Support\Number::format($item->sale_price * $item->quantity, precision: 2) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3" class="right"><strong>Total</strong></td>
                <td colspan="3" class="right"><strong>{{ Illuminate\Support\Number::format($total, precision: 2) }}
                        Bs</strong></td>
            </tr>
            <tr>
                <td colspan="6"><strong>{{ Str::upper($formater->format($total)) }} BOLIVIANOS</strong></td>
            </tr>
            <tr>
                <td colspan="6" style="height:40px;">
                    <strong>Observaciones:</strong><br>
                    {{ $contract->description }}
                </td>
            </tr>
        </tbody>
    </table>
@endsection
