@section('title')
    {{ $title }}
@endsection

@extends('layouts.main')
@section('content')
    <!-- start: page -->
    <livewire:luca.stock-list />
    <!-- end: page -->    
@endsection