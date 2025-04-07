@section('title')
    {{ $title }}
@endsection

@extends('layouts.main')
@section('content')
    <!-- start: page -->
    <livewire:inva-logistic.barcode />    
    <!-- end: page --> 
@endsection