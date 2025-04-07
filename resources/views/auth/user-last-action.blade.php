@section('title')
    {{ $title }}
@endsection

@extends('layouts.main')
@section('content')
    <!-- start: page -->
    <livewire:auth.last-action />
    <!-- end: page -->    
@endsection