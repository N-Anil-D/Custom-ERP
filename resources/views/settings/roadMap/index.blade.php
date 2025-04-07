@section('title')
    {{ $title }}
@endsection

@extends('layouts.main')
@section('content')
    <!-- start: page -->
    <livewire:settings.road-map />
    <!-- end: page -->    
@endsection