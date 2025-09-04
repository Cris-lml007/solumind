@extends('adminlte::page')
@php
    $activeTab = request()->query('tab', 'comprobantes');
@endphp

@section('content')
    <div class="d-flex justify-content-between mb-3 p-0" style="align-items: center;">
        <div>
            <h1 class="m-0">Gestión Contable</h1>
            <h6 class="m-0 p-0" style="align-self: center;"><strong>Dashboard</strong> > <strong>Comprobantes</strong></h6>
        </div>
    </div>


    <ul class="nav nav-tabs" id="contabilidadTab" role="tablist">
        <li class="nav-item" role="presentation"><button class="nav-link {{ $activeTab == 'comprobantes' ? 'active' : '' }}"
                id="comprobantes-tab" data-bs-toggle="tab" data-bs-target="#comprobantes-pane"
                type="button">Comprobantes</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link {{ $activeTab == 'proformas' ? 'active' : '' }}"
                id="proformas-tab" data-bs-toggle="tab" data-bs-target="#proformas-pane" type="button">Proformas</button>
        </li>
        <li class="nav-item" role="presentation"><button class="nav-link {{ $activeTab == 'contratos' ? 'active' : '' }}"
                id="contratos-tab" data-bs-toggle="tab" data-bs-target="#contratos-pane" type="button">Contratos</button>
        </li>
    </ul>

    <div class="tab-content" id="contabilidadTabContent">
        {{-- COMPROBANTES --}}
        <div class="tab-pane fade {{ $activeTab == 'comprobantes' ? 'show active' : '' }}" id="comprobantes-pane"
            role="tabpanel">
            <div class="card card-top-border-radius-0">
                <div class="card-body">
                    <p>Esta sección muestra un resumen de los movimientos registrados en el Libro Diario.</p>
                    <x-adminlte.tool.datatable id="table-comprobantes" :heads="$heads['comprobantes']" :config="$config">
                        @foreach ($data['comprobantes'] as $item)
                            <tr>
                                <td><strong>{{ $item->id }}</strong></td>
                                <td>{{ \Carbon\Carbon::parse($item->fecha)->format('d/m/Y') }}</td>
                                <td>
                                    @if ($item->tipo == 'Ingreso')
                                        <span class="badge badge-success">{{ $item->tipo }}</span>
                                    @elseif($item->tipo == 'Egreso')
                                        <span class="badge badge-danger">{{ $item->tipo }}</span>
                                    @else
                                        <span class="badge badge-info">{{ $item->tipo }}</span>
                                    @endif
                                </td>
                                <td>{{ $item->descripcion }}</td>
                                <td class="text-right"><strong>{{ number_format($item->monto, 2, ',', '.') }}</strong></td>
                                <td><a href="{{ route('dashboard.comprobante.form.design') }}" class="btn btn-primary"><i
                                            class="fa fa-file-pdf"></i> Exportar</a></td>
                            </tr>
                        @endforeach
                    </x-adminlte.tool.datatable>
                </div>
            </div>
        </div>

        {{-- PROFORMAS --}}
        <div class="tab-pane fade {{ $activeTab == 'proformas' ? 'show active' : '' }}" id="proformas-pane"
            role="tabpanel">
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
                                <td>{{ $item->cod }}</td>
                                <td>{{ $item->client->person->name }}<br>{{ $item->client->organization }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->create_at) }}</td>
                                <td><span class="badge {{ $item->status == 1 ? 'badge-success' : 'badge-danger' }}">{{ $item->status == 1 ? 'Activo' : 'Fallido' }}</span>
                                </td>
                                {{-- <td class="text-right"><strong>{{ number_format($item->total, 2, ',', '.') }}</strong></td> --}}
                                <td><a href="{{ route('dashboard.proof.form',$item->id) }}" class="btn btn-primary"><i
                                            class="fa fa-ellipsis-v"></i></a></td>
                            </tr>
                        @endforeach
                    </x-adminlte.tool.datatable>
                </div>
            </div>
        </div>

        {{-- CONTRATOS --}}
        <div class="tab-pane fade {{ $activeTab == 'contratos' ? 'show active' : '' }}" id="contratos-pane"
            role="tabpanel">
            <div class="card card-top-border-radius-0">
                <div class="card-body">
                    <p>Esta sección muestra los contratos generados a partir de las proformas aceptadas.</p>
                    <x-adminlte.tool.datatable id="table-contratos" :heads="$heads['contratos']" :config="$config">
                        @foreach ($data['contratos'] as $item)
                            <tr>
                                <td>{{ $item->cod }}</td>
                                <td>{{ $item->client->person->name }}</td>
                                <td>{{ $item->time_delivery }} Dias</td>
                                <td><span class="badge badge-success">{{ __($item->status->name) }}</span></td>
                                <td><a href="{{ route('dashboard.contrato.form.design') }}" class="btn btn-primary"><i
                                            class="fa fa-ellipsis-v"></i></a></td>
                            </tr>
                        @endforeach
                    </x-adminlte.tool.datatable>
                </div>
            </div>
        </div>
    </div>

    <x-modal id="modalNuevaProforma" title="Nueva Proforma" class="">
        <livewire:contract-form></livewire:contract-form>
    </x-modal>
@endsection

@section('css')
    <style>
        .text-right {
            text-align: right;
        }

        .card-top-border-radius-0 {
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }

        table td.simpleline {
            vertical-align: middle;
        }
    </style>
@endsection
