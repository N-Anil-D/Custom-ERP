@section('title')
    {{ $title }}
@endsection

@extends('layouts.main')
@section('content')
    <!-- start: page -->
    <livewire:erp.warehouse.send-products />    
    <!-- end: page --> 
@endsection