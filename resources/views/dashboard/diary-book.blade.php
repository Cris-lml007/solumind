@extends('adminlte::page')

@section('content')
    <div class="d-flex justify-content-between mb-3 p-0" style="align-items: center;">
        <div>
            <h1 class="m-0">Libro Diario</h1>
            <h6 class="m-0 p-0" style="align-self: center;"><strong>Dashboard</strong> > <strong>Libro Diario</strong></h6>
        </div>
        <button data-bs-target="#modal-transaction" data-bs-toggle="modal" class="btn btn-primary"><i class="fa fa-plus"></i> Añadir Nuevo
            Asiento</button>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="mb-3 d-flex justify-content-center">
                <div style="width: 30%;">
                    <div class="input-group">
                        <span class="input-group-text">Fecha</span>
                        <input type="date" class="form-control">
                        <button class="btn btn-primary"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </div>
            <x-adminlte.tool.datatable id="table-diary" :heads="$heads">
                <tr>
                    <td>2025/09/03</td>
                    <td></td>
                    <td>1000</td>
                    <td>inversion de Cristian</td>
                    <td>ALM-9/25</td>
                    <td>Inversión</td>
                </tr>
            </x-adminlte.tool.datatable>
        </div>
    </div>

    <x-modal id="modal-transaction" title="Nuevo Asiento" class="">
        <livewire:diary-book-form>
        </livewire:diary-book-form>
    </x-modal>
@endsection
