@php
    $t_income = 0;
    $t_expense = 0;
@endphp

<html>
<head>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">
    <style>

    </style>
</head>
<header>
    <table class="w3-table ">
        <tr>
            <th>
                <h1 style="text-align: center;margin-bottom: 1px;">{{ $title }}</h1>
                <h4 style="text-align: center;margin-top: 0;">SOLUMIND</h4>
            </th>
        </tr>
    </table>
</header>
<body>
    <div>
        <table class="w3-table-all">
            <tr>
                <th class="w3-yellow">Generado por: </th>
                <td @if($search ?? null == null || $search == '') colspan="1" @endif>{{ $user->person->name }}</td>
            </tr>
            <tr>
                <th class="w3-yellow">en Fecha: </th>
                <td @if($search ?? null == null || $search == '') colspan="1" @endif>{{ now() }}</td>
                @if ($search ?? null != null)
                <th class="w3-yellow">Por Busqueda:</th>
                    @if(!is_array($search))
                        <td>{{ $search ?? '' }}</td>
                    @else
                        <td>{{ implode(',',$search) ?? '' }}</td>
                    @endif
                @endif
            </tr>
        </table>
    </div>
    <div>
        <table class="w3-table-all">
            <thead class="w3-yellow">
                <th>ID</th>
                <th>Fecha</th>
                <th>Ingreso</th>
                <th>Egreso</th>
                <th>Descripción</th>
                <th>Contrato</th>
                <th>A Fondo</th>
                <th>A Cuenta</th>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    @php
                        $t_income += $item->type == 1 ? $item->amount : 0;
                        $t_expense += $item->type == 2 ? $item->amount : 0;
                    @endphp
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td style="white-space: nowrap;">{{ Carbon\Carbon::parse($item->date)->toDateString() }}</td>
                        <td>{{ $item->type == 1 ? Illuminate\Support\Number::format($item->amount, precision: 2) : '' }}
                        </td>
                        <td>{{ $item->type == 2 ? Illuminate\Support\Number::format($item->amount, precision: 2) : '' }}
                        </td>
                        <td>{{ $item->description }}</td>
                        <td style="white-space: nowrap;">{{ $item->contract->cod ?? '' }}</td>
                        <td>{{ __('messages.' . $item->assigned->name) }}</td>
                        <td>{{ $item->account->name ?? '' }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="w3-yellow">
                    <th colspan="2">SUBTOTAL</th>
                    <th style="white-space: nowrap;">{{ Illuminate\Support\Number::format($t_income, precision: 2) }} Bs</th>
                    <th style="white-space: nowrap;">{{ Illuminate\Support\Number::format($t_expense, precision: 2) }} Bs</th>
                </tr>
                <tr class="w3-yellow">
                    <th colspan="2">TOTAL</th>
                    <th style="white-space: nowrap;" colspan="2">{{ Illuminate\Support\Number::format($t_income - $t_expense, precision: 2) }} Bs</th>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>
