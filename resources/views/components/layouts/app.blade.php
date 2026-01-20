@extends('adminlte::page')
@section('content_header')
    <h6><strong>{{ $path_dir ?? '' }}</strong></h6>
@endsection
@section('content')
    {{ $slot }}
@endsection
