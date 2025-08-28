@extends('adminlte::page')

@section('content')
    <div class="d-flex justify-content-between mb-3 p-0" style="align-items: center;">
        <div>
            <h1 class="m-0">Detalle Proforma</h1>
            <h6 class="m-0 p-0" style="align-self: center;">
                <strong>Dashboard</strong> > 
                <a href="{{ route('dashboard.comprobante.design') }}">Gestión Contable</a> > 
                <strong>PF-001</strong>
            </h6>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
      
            <h6><strong>Información del Cliente</strong></h6>
            <div class="row">
                <div class="form-group col-md-6">
                    <label>Cliente</label>
                    <input type="text" class="form-control" value="Cliente Ejemplo A" placeholder="Buscar o seleccionar cliente...">
                </div>
                <div class="form-group col-md-3">
                    <label>Fecha de Emisión</label>
                    <input type="date" class="form-control" value="2025-08-20">
                </div>
                <div class="form-group col-md-3">
                    <label>Estado</label>
                    <select class="form-control">
                        <option>Borrador</option>
                        <option selected>Enviada</option>
                        <option>Aceptada</option>
                        <option>Rechazada</option>
                    </select>
                </div>
            </div>
            <hr/>

    
            <h6><strong>Ítems de la Proforma</strong></h6>
            <table class="table table-bordered mt-3">
                <thead class="thead">
                    <tr>
                        <th>Descripción</th>
                        <th class="text-center">Cantidad</th>
                        <th class="text-right">Precio Unit. (Bs)</th>
                        <th class="text-right">Subtotal (Bs)</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Datos de ejemplo --}}
                    <tr>
                        <td>Desarrollo de Sitio Web E-commerce</td>
                        <td class="text-center">1</td>
                        <td class="text-right">3,000.00</td>
                        <td class="text-right">3,000.00</td>
                    </tr>
                    <tr>
                        <td>Hosting y Dominio Anual</td>
                        <td class="text-center">1</td>
                        <td class="text-right">500.00</td>
                        <td class="text-right">500.00</td>
                    </tr>
                </tbody>
            </table>
         
            <div class="row justify-content-end mt-4">
                <div class="col-md-4">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th>Subtotal:</th>
                                <td class="text-right">3,500.00 Bs</td>
                            </tr>
                            <tr>
                                <th>Impuestos (IVA 13%):</th>
                                <td class="text-right">455.00 Bs</td>
                            </tr>
                            <tr>
                                <th><strong>TOTAL:</strong></th>
                                <td class="text-right"><strong>3,955.00 Bs</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <hr/>

          
            <h6><strong>Términos y Condiciones</strong></h6>
            <div class="form-group">
                <textarea class="form-control" rows="3">Validez de la oferta: 15 días. Condiciones de pago: 50% de adelanto, 50% contra entrega.</textarea>
            </div>

        </div>
    </div>


    <div class="d-flex justify-content-end mt-3">
        <a href="{{ route('dashboard.comprobante.design', ['tab' => 'contratos']) }}" class="btn btn-primary mr-2">Generar Contrato</a>
        <a href="#" class="btn btn-warning mr-2">Modificar</a>
        <a href="#" class="btn btn-danger">Eliminar</a>
    </div>
@endsection

@section('css')
    <style>
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
@endsection