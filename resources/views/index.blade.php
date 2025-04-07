@section('title')
    {{ $title }}
@endsection

@extends('layouts.main')
@section('content')
    <!-- start: page -->
    <livewire:lw-main-page />
    <!-- end: page -->
    
    @include('parts.alertify')
@endsection
