@extends('adminlte::page')
@section('title', 'Productos')
@section('content')
    <div class="d-flex justify-content-between mb-3 p-0" style="align-items: center;">
        <div>
            <h1 class="m-0">Lista de Productos</h1>
            <h6 class="m-0 p-0" style="align-self: center;"><strong>Dashboard</strong> > <strong>Productos</strong></h6>
        </div>
        @can('product-permission', 3)
            <button data-bs-target="#modal" data-bs-toggle="modal" class="btn btn-primary"><i class="fa fa-plus"></i> Añadir Nuevo
                Producto</button>
        @endcan
    </div>

    <div class="card">
        <div class="card-body">
            <x-adminlte.tool.datatable id="table1" :heads="$heads" :config="$config">
                @foreach ($data as $item)
                    <tr>
                        <td><strong>{{ $item->id }}</strong></td>
                        <td><strong>{{ $item->name . ' ' . $item->size }}</strong></td>
                        <td><strong>{{ $item->supplier->organization == null ? $item->supplier->person->name : $item->supplier->organization }}</strong>
                        </td>
                        <td><strong>{{ Illuminate\Support\Number::format($item->price, precision: 2) }}</strong></td>
                        <td><a href="{{ route('dashboard.product.form', $item->id) }}" class="btn btn-primary"><i
                                    class="fa fa-ellipsis-v"></i></a>
                        </td>
                    </tr>
                @endforeach
            </x-adminlte.tool.datatable>
        </div>
    </div>
    <x-modal id="modal" title="Nuevo Producto" class="modal-lg">
        <livewire:product-form></livewire:product-form>
    </x-modal>
@endsection

@section('css')
    <style>
        table td.simpleline {
            vertical-align: middle;
        }
    </style>
@endsection
