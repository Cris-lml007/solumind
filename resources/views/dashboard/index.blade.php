@extends('adminlte::page')

@section('title', 'Principal')

@section('content_header')
    <h1>Tablero Principal</h1>
@stop

@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h6>Capital <i class="nf nf-fa-building"></i></h6>
                    <h4 class="text-primary">{{Illuminate\Support\Number::format($patrimony,precision:2)}} Bs</h4>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h6>Ventas Totales <i class="nf nf-fa-shopping_cart"></i></h6>
                    <h4 class="text-primary">{{Illuminate\Support\Number::format($total,precision:2)}} Bs</h4>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h6>Ganancia Neta <i class="nf nf-fa-money_bill"></i></h6>
                    <h4 class="text-primary">{{Illuminate\Support\Number::format($utotal,precision:2)}} Bs</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h6>Transactiones de Hoy</h6>
            <x-adminlte.tool.datatable id="table1" :heads="['ID','Ingreso','Egreso','Contrato']">
                @foreach ($data as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->type == 1 ? Illuminate\Support\Number::format($item->amount,precision:2) : '' }}</td>
                    <td>{{ $item->type == 2 ? Illuminate\Support\Number::format($item->amount,precision:2) : ''}}</td>
                    <td>{{ $item->contract->cod ?? '' }}</td>
                </tr>
                @endforeach
            </x-adminlte.tool.datatable>
        </div>
    </div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");
    </script>
@stop
