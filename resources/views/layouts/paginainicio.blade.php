@extends('layouts.base')

@section('styles')
@vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/paginainicio.css') }}">
    @yield('app-styles')
@endsection

@section('content')
    @yield('app-content')
@endsection

@section('scripts')
    @yield('app-scripts')
@endsection

