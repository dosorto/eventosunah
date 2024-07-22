@extends('layouts.base')

@section('styles')
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v3.0.6/css/line.css">
    <link rel="stylesheet" href="{{ asset('css/loginStyles.css') }}">
    @yield('app-styles')
@endsection

@section('content')
    @yield('app-content')
@endsection

@section('scripts')
    <script src="{{ asset('js/loginScripts.js') }}"></script>
    @yield('app-scripts')
@endsection
