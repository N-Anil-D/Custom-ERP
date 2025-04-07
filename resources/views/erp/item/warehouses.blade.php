@section('title')
    {{ $title }}
@endsection

@extends('layouts.main')
@section('content')
    <!-- start: page -->
    <livewire:erp.item.warehouses />    
    <!-- end: page --> 
@endsection