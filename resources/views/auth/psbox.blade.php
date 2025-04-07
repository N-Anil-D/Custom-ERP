@section('title')
    {{ $title }}
@endsection

@extends('layouts.main')
@section('content')
    <!-- start: page -->
    <livewire:auth.ps-box />
    <!-- end: page -->    
@endsection