@section('title')
    {{ $title }}
@endsection

@extends('layouts.main')
@section('content')

    @if(Session::has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ Session::get('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true" aria-label="Close"></button>
    </div>
    @endif
    <!-- start: page -->
    <livewire:erp.warehouse.my-productions />    
    <!-- end: page --> 
@endsection