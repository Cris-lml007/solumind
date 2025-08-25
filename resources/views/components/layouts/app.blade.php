@extends('adminlte::page')
@section('content_header')
<h1>{{ $title ?? 'Dashboard' }}</h1>
<h6><strong>{{ $path_dir ?? '' }}</strong></h6>
@endsection
@section('content')
    {{ $slot }}
@endsection
