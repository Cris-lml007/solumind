@extends('adminlte::page')

@section('content')
    <div class="d-flex justify-content-between mb-3 p-0" style="align-items: center;">
        <div>
            <h1 class="m-0">Detalle Contrato</h1>
            <h6 class="m-0 p-0" style="align-self: center;">
                <strong>Dashboard</strong> > 
                <a href="">Gestión Contable</a> > 
                <strong>C-2025-01</strong>
            </h6>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            
            <h6><strong>Información General</strong></h6>
            <div class="row">
                <div class="form-group col-md-6"><label>Nombre del Contrato</label><input type="text" class="form-control" value="Contrato de Desarrollo Web"></div>
                <div class="form-group col-md-6"><label>Cliente</label><input type="text" class="form-control" value="Cliente Ejemplo B" disabled></div>
                <div class="form-group col-md-6"><label>Fecha de Inicio</label><input type="date" class="form-control" value="2025-09-01"></div>
                <div class="form-group col-md-6"><label>Estado</label><input type="text" class="form-control" value="Activo" disabled></div>
            </div>
            <hr/>
            <h6><strong>Cláusulas y Condiciones</strong></h6>
            <p>Aquí iría el contenido del contrato...</p>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-3">
        <a href="#" class="btn btn-info mr-2"><i class="fa fa-file-pdf"></i> Exportar a PDF</a>
        <a href="#" class="btn btn-warning mr-2">Modificar</a>
        <a href="#" class="btn btn-danger">Eliminar</a>
    </div>
@endsection