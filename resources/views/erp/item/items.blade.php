@section('title')
    {{ $title }}
@endsection

@extends('layouts.main')
@section('content')
    <!-- start: page -->
    <livewire:erp.item.items />    
    <!-- end: page --> 
@endsection