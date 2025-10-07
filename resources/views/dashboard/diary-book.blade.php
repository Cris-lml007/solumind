@extends('adminlte::page')
@section('title', 'Libro Diario')
@section('content')
    <div class="d-flex justify-content-between mb-3 p-0" style="align-items: center;">
        <div>
            <h1 class="m-0">Libro Diario</h1>
            <h6 class="m-0 p-0" style="align-self: center;"><strong>Dashboard</strong> > <strong>Libro Diario</strong></h6>
        </div>
        @can('transaction-permission', 3)
            <button data-bs-target="#modal-transaction" data-bs-toggle="modal" class="btn btn-primary"><i class="fa fa-plus"></i>
                Añadir Nuevo
                Asiento</button>
        @endcan
    </div>
    <div class="card">
        <div class="card-body">
            <x-adminlte.tool.datatable id="table-diary" :heads="$heads" hoverable with-buttons>
                @php
                    $t_income = 0;
                    $t_expense = 0;
                @endphp
                @foreach ($data as $item)
                    @php
                        $t_income += $item->type == 1 ? $item->amount : 0;
                        $t_expense += $item->type == 2 ? $item->amount : 0;
                    @endphp
                    <tr onclick="edit({{ $item->id }})" class="item-table">
                        <td>{{ $item->id }}</td>
                        <td>{{ Carbon\Carbon::parse($item->date)->toDateString() }}</td>
                        <td>{{ $item->type == 1 ? Illuminate\Support\Number::format($item->amount, precision: 2) : '' }}</td>
                        <td>{{ $item->type == 2 ? Illuminate\Support\Number::format($item->amount, precision: 2) : '' }}</td>
                        <td>{{ $item->description }}</td>
                        <td>{{ $item->contract->cod ?? '' }}</td>
                        <td>{{ $item->account->name ?? '' }}</td>
                    </tr>
                @endforeach
                <tfoot>
                    <tr>
                        <th>TOTAL</th>
                        <td class="bg-primary"><strong>{{ Illuminate\Support\Number::format($t_income, precision: 2) }}
                                Bs</strong></td>
                        <td class="bg-secondary"><strong>{{ Illuminate\Support\Number::format($t_expense, precision: 2) }}
                                Bs</strong></td>
                    </tr>
                    <tr>
                        <th>SALDO EFECTIVO</th>
                        <td colspan="2" style="text-align: center;"
                            class="{{ $t_income - $t_expense >= 0 ? 'bg-success' : 'bg-danger' }}">
                            <strong>{{ Illuminate\Support\Number::format($t_income - $t_expense, precision: 2) }}
                                Bs</strong></td>
                    </tr>
                </tfoot>
            </x-adminlte.tool.datatable>
        </div>
    </div>

    <x-modal id="modal-transaction" title="Nuevo Asiento" class="">
        <livewire:diary-book-form>
        </livewire:diary-book-form>
    </x-modal>
@endsection

@section('css')
    <style>
        .item-table {
            cursor: pointer;
        }
    </style>
@endsection

@section('js')
    <script>
        function edit($id) {
            window.location.replace("{{ route('dashboard.diary_book.form', 'q') }}".slice(0, -1) + $id)
            {{-- console.log("{{ route('dashboard.diary_book.form','q') }}".slice(0,-1)) --}}
        }
    </script>
@endsection
