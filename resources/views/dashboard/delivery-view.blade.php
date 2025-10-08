@extends('adminlte::page')
@section('title', 'Entregas')
@section('content')
    <div class="d-flex justify-content-between mb-3 p-0" style="align-items: center;">
        <div>
            <h1 class="m-0">Lista de Entregas</h1>
            <h6 class="m-0 p-0" style="align-self: center;"><strong>Dashboard</strong> > <strong>Entregas</strong></h6>
        </div>
        @can('delivery-permission', 3)
            <button data-bs-target="#modal" data-bs-toggle="modal" class="btn btn-primary"><i class="fa fa-plus"></i> Registrar
                Entrega</button>
        @endcan
    </div>
    <div class="card">
        <div class="card-body">
            <x-adminlte.tool.datatable id="table" :heads="$heads">
                @foreach ($data as $item)
                    <tr>
                        <td>{{ $item->date }}</td>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->contract->cod }}</td>
                        @can('delivery-permission', 3)
                            <td>
                                <a target="_blank" href="{{ route('dashboard.delivery.pdf', $item->id) }}" class="btn btn-primary"><i
                                        class="fa fa-file"></i></a>
                                @if ($item->is_canceled == 1 && $item->contract->status->value == 3)
                                    <a href="{{ route('dashboard.delivery.form', $item->id) }}" class="btn btn-secondary"><i
                                            class="fa fa-pen"></i></a>
                                @endif
                            </td>
                        @else
                            <td></td>
                        @endcan
                    </tr>
                @endforeach
            </x-adminlte.tool.datatable>
        </div>
    </div>

    <x-modal id="modal" class="modal-lg" title="Registrar Entrega">
        <livewire:delivery-form></livewire:delivery-form>
    </x-modal>
@endsection
