@extends('adminlte::page')

@section('content')
    <div class="d-flex justify-content-between mb-3 p-0" style="align-items: center;">
        <div>
            <h1 class="m-0">Gestión Contable</h1>
            <h6 class="m-0 p-0" style="align-self: center;"><strong>Dashboard</strong> > <strong>Comprobantes</strong></h6>
        </div>
    </div>

    @php
        $activeTab = request()->query('tab', 'comprobantes');
    @endphp

    <ul class="nav nav-tabs" id="contabilidadTab" role="tablist">
        <li class="nav-item" role="presentation"><button class="nav-link {{ $activeTab == 'comprobantes' ? 'active' : '' }}" id="comprobantes-tab" data-bs-toggle="tab" data-bs-target="#comprobantes-pane" type="button">Comprobantes</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link {{ $activeTab == 'proformas' ? 'active' : '' }}" id="proformas-tab" data-bs-toggle="tab" data-bs-target="#proformas-pane" type="button">Proformas</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link {{ $activeTab == 'contratos' ? 'active' : '' }}" id="contratos-tab" data-bs-toggle="tab" data-bs-target="#contratos-pane" type="button">Contratos</button></li>
    </ul>

    <div class="tab-content" id="contabilidadTabContent">
        {{-- COMPROBANTES --}}
        <div class="tab-pane fade {{ $activeTab == 'comprobantes' ? 'show active' : '' }}" id="comprobantes-pane" role="tabpanel">
            <div class="card card-top-border-radius-0"><div class="card-body">
                <p>Esta sección muestra un resumen de los movimientos registrados en el Libro Diario.</p>
                <x-adminlte.tool.datatable id="table-comprobantes" :heads="$heads['comprobantes']" :config="$config">
                    @foreach ($data['comprobantes'] as $item)
                        <tr>
                            <td><strong>{{ $item->id }}</strong></td>
                            <td>{{ \Carbon\Carbon::parse($item->fecha)->format('d/m/Y') }}</td>
                            <td>
                                @if($item->tipo == 'Ingreso') <span class="badge badge-success">{{ $item->tipo }}</span>
                                @elseif($item->tipo == 'Egreso') <span class="badge badge-danger">{{ $item->tipo }}</span>
                                @else <span class="badge badge-info">{{ $item->tipo }}</span>
                                @endif
                            </td>
                            <td>{{ $item->descripcion }}</td>
                            <td class="text-right"><strong>{{ number_format($item->monto, 2, ',', '.') }}</strong></td>
                            <td><a href="{{ route('dashboard.comprobante.form.design') }}" class="btn btn-primary"><i class="fa fa-file-pdf"></i> Exportar</a></td>
                        </tr>
                    @endforeach
                </x-adminlte.tool.datatable>
            </div></div>
        </div>
        
        {{-- PROFORMAS --}}
        <div class="tab-pane fade {{ $activeTab == 'proformas' ? 'show active' : '' }}" id="proformas-pane" role="tabpanel">
            <div class="card card-top-border-radius-0">
                <div class="card-header d-flex justify-content-end border-bottom-0">
                    <button data-bs-target="#modalNuevaProforma" data-bs-toggle="modal" class="btn btn-primary">
                        <i class="fa fa-plus"></i> Crear Nueva Proforma
                    </button>
                </div>
                <div class="card-body">
                    <x-adminlte.tool.datatable id="table-proformas" :heads="$heads['proformas']" :config="$config">
                        @foreach ($data['proformas'] as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->cliente }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->fecha)->format('d/m/Y') }}</td>
                                <td><span class="badge badge-warning">{{ $item->estado }}</span></td>
                                <td class="text-right"><strong>{{ number_format($item->total, 2, ',', '.') }}</strong></td>
                                <td><a href="{{ route('dashboard.proforma.form.design') }}" class="btn btn-primary"><i class="fa fa-ellipsis-v"></i></a></td>
                            </tr>
                        @endforeach
                    </x-adminlte.tool.datatable>
                </div>
            </div>
        </div>

        {{-- CONTRATOS --}}
        <div class="tab-pane fade {{ $activeTab == 'contratos' ? 'show active' : '' }}" id="contratos-pane" role="tabpanel">
             <div class="card card-top-border-radius-0"><div class="card-body">
                <p>Esta sección muestra los contratos generados a partir de las proformas aceptadas.</p>
                <x-adminlte.tool.datatable id="table-contratos" :heads="$heads['contratos']" :config="$config">
                     @foreach ($data['contratos'] as $item)
                         <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->nombre }}</td>
                            <td>{{ $item->cliente }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->fecha_inicio)->format('d/m/Y') }}</td>
                            <td><span class="badge badge-success">{{ $item->estado }}</span></td>
                            <td><a href="{{ route('dashboard.contrato.form.design') }}" class="btn btn-primary"><i class="fa fa-ellipsis-v"></i></a></td>
                        </tr>
                    @endforeach
                </x-adminlte.tool.datatable>
            </div></div>
        </div>
    </div>

    <x-modal id="modalNuevaProforma" title="Nueva Proforma" class="modal-lg">
        
        <h6><strong>Información General</strong></h6>
        <div class="d-flex">
            <div style="width: 50%;" class="pr-2">
                <label>Cliente</label>
                <input type="text" class="form-control mb-3" placeholder="Buscar cliente existente">
            </div>
            <div style="width: 50%;" class="pl-2">
                <label>Fecha de Emisión</label>
                <input type="date" class="form-control mb-3" value="{{ now()->format('Y-m-d') }}">
            </div>
        </div>
        <div class="d-flex">
            <div style="width: 50%;" class="pr-2">
                <label>Estado</label>
                <select class="form-control mb-3">
                    <option>Borrador</option>
                    <option selected>Enviada</option>
                    <option>Aceptada</option>
                    <option>Rechazada</option>
                </select>
            </div>
            <div style="width: 50%;" class="pl-2">
                <label>Validez (días)</label>
                <input type="number" class="form-control mb-3" value="15">
            </div>
        </div>

        <hr/>

        <h6><strong></strong></h6>
        <p></p>
        
            <div class="modal-footer">
            <button class="btn btn-primary" wire:click="save">Guardar</button>
            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
        
    </x-modal>

@endsection

@section('css')
    <style>
        .text-right { text-align: right; }
        .card-top-border-radius-0 { border-top-left-radius: 0; border-top-right-radius: 0; }
        table td.simpleline { vertical-align: middle; }
    </style>
@endsection