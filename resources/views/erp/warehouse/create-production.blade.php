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
    <livewire:erp.warehouse.create-production :userId='Auth::user()->id' :itemId='$item->id' :warehouseId='$warehouse->id' :productionId='$productionId' />    
    <!-- end: page --> 
@endsection