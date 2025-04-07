@section('title')
    {{ $title }}
@endsection

@extends('layouts.main')
@section('content')
    <!-- start: page -->
    <livewire:erp.stock-taking.confirm-count />  
    <!-- end: page --> 
@endsection