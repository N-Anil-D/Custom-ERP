@section('title')
    {{ $title }}
@endsection

@extends('layouts.main')
@section('content')
    <!-- start: page -->
    <livewire:settings.user-manage />    
    <!-- end: page -->    
@endsection
