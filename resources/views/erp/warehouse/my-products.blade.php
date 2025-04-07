@section('title')
    {{ $title }}
@endsection

@extends('layouts.main')
@section('content')
    <!-- start: page -->
    <livewire:erp.warehouse.my-products />    
    <!-- end: page --> 
@endsection