@extends('adminlte::page')

@section('content')
    <div class="d-flex justify-content-between mb-3 p-0" style="align-items: center;">
        <div>
  
            <h1 class="m-0">Detalle Comprobante</h1>
            <h6 class="m-0 p-0" style="align-self: center;">
                <strong>Dashboard</strong> > 
                <a href="{{ route('dashboard.comprobante.design') }}">Comprobantes</a> > 
                <strong>1</strong> 
            </h6>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
     
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Fecha</label>
                    <input type="date" class="form-control" value="2025-08-27">
                </div>
                <div class="form-group col-md-6">
                    <label>Tipo de Comprobante</label>
                    <select class="form-control">
                        <option selected>Ingreso</option>
                        <option>Egreso</option>
                        <option>Transferencia</option>
                    </select>
                </div>
                <div class="form-group col-12">
                    <label>Descripción</label>
                    <textarea class="form-control" rows="3">Venta de 10 unidades del producto X</textarea>
                </div>
                <div class="form-group col-md-6">
                    <label>Monto (Bs)</label>
                    <input type="number" step="0.01" class="form-control" value="1250.50">
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-3">
        <a href="#" class="btn btn-warning mr-2">Modificar</a>
        <a href="#" class="btn btn-danger">Eliminar</a>
    </div>
@endsection