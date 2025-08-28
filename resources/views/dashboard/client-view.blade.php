@extends('adminlte::page')

@section('content')
    <div class="d-flex justify-content-between mb-3 p-0" style="align-items: center;">
        <div>
            <h1 class="m-0">Lista de Clientes</h1>
            <h6 class="m-0 p-0" style="align-self: center;"><strong>Dashboard</strong> > <strong>Clientes</strong></h6>
        </div>
        <button data-bs-target="#modal" data-bs-toggle="modal" class="btn btn-primary">
            <i class="fa fa-plus"></i> Añadir Nuevo Cliente</button>
    </div>

    <div class="card">
        <div class="card-body">
            <x-adminlte.tool.datatable id="table1" :heads="$heads" :config="$config">
                @foreach ($data as $item)
                    <tr>
                        <td class="simpleline"><strong>{{ $item->person->ci }}</strong></td>
                        <td class="simpleline"><strong>{{ $item->nit }}</strong></td>
                        <td><strong>{{ $item->person->name }}</strong><br>{{ $item->organization }}</td>
                        <td><strong>{{ $item->email == null ? $item->person->email : $item->email }}</strong><br>{{ $item->phone == null ? $item->person->phone : $item->phone }}
                        {{-- <td><strong>{{ $item->person->email }} --}}
                        {{-- <td></strong>{{ $item->phone }}</td> --}}
                        {{-- <td></strong>{{ $item->person->ci }}</td> --}}
                        <td><a href="{{route('dashboard.client.form',$item->id)}}" class="btn btn-primary"><i class="fa fa-ellipsis-v"></i></a>
                        </td>
                    </tr>
                @endforeach
            </x-adminlte.tool.datatable>
        </div>
    </div>
    <x-modal id="modal" title="Nuevo Cliente" class="modal-lg">
        <livewire:client-form></livewire:client-form>
    </x-modal>
@endsection

@section('css')
    <style>
        table td.simpleline {
            vertical-align: middle;
        }
    </style>
@endsection

@section('js')
@endsection
