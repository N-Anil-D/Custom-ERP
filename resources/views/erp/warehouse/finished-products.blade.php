@section('title')
    {{ $title }}
@endsection

@extends('layouts.main')
@section('content')
    <!-- start: page -->
    <livewire:erp.warehouse.finished-products />    
    <!-- end: page --> 
@endsection