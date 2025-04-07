@section('title')
    {{ $title }}
@endsection
@section('css')
    <link rel="stylesheet" href="{{ url('panel/css/outline-buttons.css') }}" />
    <style>
        .modal-fullscreen{
            max-width: none !important;
        }
    </style>
@endsection

@extends('layouts.main')
@section('content')
    <!-- start: page -->
    <livewire:erp.warehouse.warehouse-items />    
    <!-- end: page --> 
@endsection