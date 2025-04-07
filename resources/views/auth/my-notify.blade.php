@section('title')
    {{ $title }}
@endsection

@extends('layouts.main')
@section('content')
    <!-- start: page -->
    <livewire:auth.my-notify />
    <!-- end: page -->    
@endsection