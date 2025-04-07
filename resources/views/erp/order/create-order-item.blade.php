@section('title')
    {{ $title }}
@endsection

@extends('layouts.main')
@section('content')
    <!-- start: page -->
    <livewire:erp.order.create-order-item />    
    <!-- end: page --> 
@endsection