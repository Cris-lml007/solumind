@extends('adminlte::page')

@section('content')
    <div class="d-flex justify-content-between mb-3 p-0" style="align-items: center;">
        <div>
            <h1 class="m-0">Lista de Proveedores</h1>
            <h6 class="m-0 p-0" style="align-self: center;"><strong>Dashboard</strong> > <strong>Proveedores</strong></h6>
        </div>
        <button data-bs-target="#modal" data-bs-toggle="modal" class="btn btn-primary"><i class="fa fa-plus"></i> Añadir Nuevo
            Proveedor</button>
    </div>

    <div class="card">
        <div class="card-body">
            <x-adminlte.tool.datatable id="table1" :heads="$heads" :config="$config">
                <tr>
                    <td class="simpleline"><strong>7329034</strong></td>
                    <td><strong>Cristian Manuel Abalos</strong><br>CoffySoft</td>
                    <td><strong>cristianmanuel007@gmail.com</strong><br>61813282</td>
                    <td class="simpleline"><strong>Social</strong></td>
                    <td class="simpleline"><button class="btn btn-primary"><i class="fa fa-ellipsis-v"></i></button></td>
                </tr>
            </x-adminlte.tool.datatable>
        </div>
    </div>
    <x-modal id="modal" title="Nuevo Proveedor" class="modal-lg">
        <livewire:supplier-form></livewire:supplier-form>
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
