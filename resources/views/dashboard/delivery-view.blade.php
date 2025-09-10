@extends('adminlte::page')

@section('content')
    <div class="d-flex justify-content-between mb-3 p-0" style="align-items: center;">
        <div>
            <h1 class="m-0">Lista de Entregas</h1>
            <h6 class="m-0 p-0" style="align-self: center;"><strong>Dashboard</strong> > <strong>Entregas</strong></h6>
        </div>
        <button data-bs-target="#modal" data-bs-toggle="modal" class="btn btn-primary"><i class="fa fa-plus"></i> Registrar
            Entrega</button>
    </div>
    <div class="card">
        <div class="card-body">
            <x-adminlte.tool.datatable id="table" :heads="$heads">
                @foreach ($data as $item)
                    <tr>
                        @php
                            $balance =
                                $item->contract?->detail_contract()?->sum(DB::raw('sale_price*quantity')) -
                                $item->contract
                                    ?->transactions()
                                    ->where('account_id', 2)
                                    ->sum('amount');
                        @endphp
                        <td>{{ $item->date }}</td>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->contract->cod }}</td>
                        <td>{{ $item->amount }}</td>
                        <td>{{ $balance }}</td>
                    </tr>
                @endforeach
            </x-adminlte.tool.datatable>
        </div>
    </div>

    <x-modal id="modal" class="modal-lg" title="Registrar Entrega">
        <livewire:delivery-form></livewire:delivery-form>
    </x-modal>
@endsection
