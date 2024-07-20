@extends('layouts.base')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection
@section('content')
    @yield('content')
@endsection

@section('scripts')
    <script src="{{ asset('js/login.js') }}"></script>
@endsection
