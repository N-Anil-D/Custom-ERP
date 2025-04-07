@section('title')
    {{ $title }}
@endsection

@extends('layouts.main')
@section('content')
    <!-- start: page -->
    <livewire:settings.road-map-detail />
    <!-- end: page -->    
@endsection