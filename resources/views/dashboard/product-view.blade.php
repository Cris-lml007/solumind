@extends('adminlte::page')

@section('content')
    <div class="d-flex justify-content-between mb-3 p-0" style="align-items: center;">
        <div>
            <h1 class="m-0">Lista de Productos</h1>
            <h6 class="m-0 p-0" style="align-self: center;"><strong>Dashboard</strong> > <strong>Productos</strong></h6>
        </div>
        <button data-bs-target="#modal" data-bs-toggle="modal" class="btn btn-primary"><i class="fa fa-plus"></i> Añadir Nuevo
            Producto</button>
    </div>

    <div class="card">
        <div class="card-body">
            <x-adminlte.tool.datatable id="table1" :heads="$heads" :config="$config">
                @foreach ($data as $item)
                    <tr>
                        <td><strong>{{ $item->id }}</strong></td>
                        @php
                            $words = explode(' ', $item->name);
                            $s = 0;
                            foreach ($words as $word) {
                                if (strlen($word) > 2) {
                                    $s += 3;
                                }
                            }
                            $size = substr($item->cod, $s, strlen($item->cod));
                        @endphp
                        <td><strong>{{ $item->name . ' ' . $size }}</strong></td>
                        <td><strong>{{ $item->supplier->organization == null ? $item->supplier->person->name : $item->supplier->organization }}</strong>
                        </td>
                        <td><strong>{{ $item->price }}</strong></td>
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
