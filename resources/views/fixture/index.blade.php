@section('title')
    {{ $title }}
@endsection

@extends('layouts.main')
@section('content')
    <!-- start: page -->
    <livewire:fixtures.list-items />
    <!-- end: page --> 
@endsection