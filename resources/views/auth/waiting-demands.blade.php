@section('title')
    {{ $title }}
@endsection

@extends('layouts.main')
@section('content')
    <!-- start: page -->
    <livewire:auth.waiting-demands />
    <!-- end: page -->    
@endsection