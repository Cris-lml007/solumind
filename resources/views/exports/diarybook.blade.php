@php
    $t_income = 0;
    $t_expense = 0;
@endphp
<table>
    <thead>
        <tr>
            <th colspan="8" style="background-color: #3765FE;color: white;text-align: center;">{{ $title }}</th>
        </tr>
        <tr>
            <th style="background-color: #3765FE;color: white;">ID</th>
            <th style="background-color: #3765FE;color: white;">Fecha</th>
            <th style="background-color: #3765FE;color: white;">Ingreso</th>
            <th style="background-color: #3765FE;color: white;">Egreso</th>
            <th style="background-color: #3765FE;color: white;">Descripción</th>
            <th style="background-color: #3765FE;color: white;">Contrato</th>
            <th style="background-color: #3765FE;color: white;">A Fondo</th>
            <th style="background-color: #3765FE;color: white;">A Cuenta</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            @php
                $t_income += $item->type == 1 ? $item->amount : 0;
                $t_expense += $item->type == 2 ? $item->amount : 0;
            @endphp
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ Carbon\Carbon::parse($item->date)->toDateString() }}</td>
                <td>{{ $item->type == 1 ? Illuminate\Support\Number::format($item->amount, precision: 2) : '' }}
                </td>
                <td>{{ $item->type == 2 ? Illuminate\Support\Number::format($item->amount, precision: 2) : '' }}
                </td>
                <td>{{ $item->description }}</td>
                <td>{{ $item->contract->cod ?? '' }}</td>
                <td>{{ __('messages.' . $item->assigned->name) }}</td>
                <td>{{ $item->account->name ?? '' }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2" style="background-color: #3765FE;color: white;">SUBTOTAL</th>
            <th style="background-color: #7BFB5F;color: black;">{{ Illuminate\Support\Number::format($t_income, precision: 2) }} Bs</th>
            <th style="background-color: #FD6A6A;color: black;">{{ Illuminate\Support\Number::format($t_expense, precision: 2) }} Bs</th>
        </tr>
        <tr>
            <th colspan="2" style="background-color: #3765FE;color: white;">TOTAL</th>
            <th colspan="2" style="background-color: yellow;color: black;" >{{ Illuminate\Support\Number::format($t_income - $t_expense, precision: 2) }} Bs</th>
        </tr>
    </tfoot>
</table>
