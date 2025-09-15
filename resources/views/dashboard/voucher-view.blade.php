@extends('adminlte::page')
@section('title', 'Comprobantes')
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

        <div class="d-flex w-50">
            <li class="nav-item" role="presentation"><button
                    class="nav-link {{ $activeTab == 'comprobantes' ? 'active' : '' }}" id="comprobantes-tab"
                    data-bs-toggle="tab" data-bs-target="#comprobantes-pane" type="button">Comprobantes</button></li>
            <li class="nav-item" role="presentation"><button
                    class="nav-link {{ $activeTab == 'proformas' ? 'active' : '' }}" id="proformas-tab" data-bs-toggle="tab"
                    data-bs-target="#proformas-pane" type="button">Proformas</button>
            </li>
            <li class="nav-item" role="presentation"><button
                    class="nav-link {{ $activeTab == 'contratos' ? 'active' : '' }}" id="contratos-tab" data-bs-toggle="tab"
                    data-bs-target="#contratos-pane" type="button">Contratos</button>
            </li>
        </div>
        <div class="d-flex justify-content-end w-50">
            @can('voucher-permission', 3)
                <button data-bs-target="#modalNuevaProforma" data-bs-toggle="modal" class="btn btn-primary"
                    style="height: 38px;">
                    <i class="fa fa-plus"></i> Crear Nueva Proforma
                </button>
            @endcan
        </div>
    </ul>

    <div class="tab-content" id="contabilidadTabContent">
        {{-- COMPROBANTES --}}
        <div class="tab-pane fade {{ $activeTab == 'comprobantes' ? 'show active' : '' }}" id="comprobantes-pane"
            role="tabpanel">
            <div class="card card-top-border-radius-0">
                <div class="card-body">
                    <x-adminlte.tool.datatable id="table-comprobantes" :heads="$heads['comprobantes']" :config="$config">
                        @foreach ($data['comprobantes'] as $item)
                            <tr>
                                <td><strong>{{ $item->id }}</strong></td>
                                <td>{{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                                <td>
                                    @if ($item->type == 1)
                                        <span class="badge badge-success">Ingreso</span>
                                    @elseif($item->type == 2)
                                        <span class="badge badge-danger">Egreso</span>
                                    @endif
                                </td>
                                <td>{{ $item->description }}</td>
                                <td><strong>{{ Illuminate\Support\Number::format($item->amount, precision: 2) }}</strong>
                                </td>
                                @can('voucher-permission', 3)
                                    <td><a href="{{ route('dashboard.proof.pdf', $item->id) }}" class="btn btn-primary"><i
                                                class="fa fa-file-pdf"></i> Exportar</a></td>
                                @else
                                    <td></td>
                                @endcan
                            </tr>
                        @endforeach
                    </x-adminlte.tool.datatable>
                </div>
            </div>
        </div>

        {{-- PROFORMAS --}}
        <div class="tab-pane fade {{ $activeTab == 'proformas' ? 'show active' : '' }}" id="proformas-pane" role="tabpanel">
            <div class="card card-top-border-radius-0">
                <div class="card-body">
                    <x-adminlte.tool.datatable id="table-proformas" :heads="$heads['proformas']" :config="$config">
                        @foreach ($data['proformas'] as $item)
                            <tr>
                                <td><strong>{{ $item->id }}</strong></td>
                                <td>{{ $item->cod }}</td>
                                <td>{{ $item->client->person->name }}<br>{{ $item->client->organization }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->create_at) }}</td>
                                <td><span
                                        class="badge {{ $item->status->value == 1 && Carbon\Carbon::parse($item->created_at)->diffInDays(Carbon\Carbon::parse(now())) <= $item->time_valide ? 'badge-success' : 'badge-danger' }}">{{ $item->status->value == 1 ? 'Activo' : 'Fallido' }}</span>
                                </td>
                                {{-- <td class="text-right"><strong>{{ number_format($item->total, 2, ',', '.') }}</strong></td> --}}
                                <td>
                                    <a href="{{ route('dashboard.proof.form', $item->id) }}" class="btn btn-primary"><i
                                            class="fa fa-ellipsis-v"></i></a>
                                    <a class="btn btn-secondary"><i class="fa fa-file" href="#"></i></a>
                                </td>
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
                    <x-adminlte.tool.datatable id="table-contratos" :heads="$heads['contratos']" :config="$config">
                        @foreach ($data['contratos'] as $item)
                            <tr>
                                <td><strong>{{ $item->id }}</strong></td>
                                <td>{{ $item->cod }}</td>
                                <td>{{ $item->client->person->name }}</td>
                                <td>{{ $item->time_delivery }} Dias</td>
                                <td><span
                                        class="badge {{ $item->status->value == 3 || $item->status->value == 5 ? 'badge-success' : 'badge-danger' }}">{{ __($item->status->name) }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('dashboard.proof.form', $item->id) }}" class="btn btn-primary"><i
                                            class="fa fa-ellipsis-v"></i></a>
                                    <a class="btn btn-secondary"><i class="fa fa-file" href="#"></i></a>
                                </td>
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
