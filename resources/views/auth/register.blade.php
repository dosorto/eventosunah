@extends('layouts.base')

@section('content')
    <x-public-auth-header />

    <livewire:auth.register-wizard />
@endsection
