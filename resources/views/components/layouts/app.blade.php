@extends('adminlte::page')
@section('content_header')
    <h1>{{ $title ?? 'Dashboard' }}</h1>
@endsection
@section('content')
    {{ $slot }}
@endsection
