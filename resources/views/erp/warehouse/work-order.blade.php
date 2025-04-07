@section('title')
    {{ $title }}
@endsection
{{-- 
    <link rel="stylesheet" href="{{ url('panel/css/outline-buttons.css') }}" />
    <style>
        .modal-fullscreen{
            max-width: none !important;
        }
    </style>
--}}
    @section('css')
		{{-- <link rel="stylesheet" href="{{ url('panel/vendor/bootstrap-fileupload/bootstrap-fileupload.min.css') }}" /> --}}
    @endsection
@extends('layouts.main')
@section('content')
    <!-- start: page -->
    <livewire:erp.warehouse.work-order />
    <!-- end: page --> 
    @section('js')
        {{-- <script src="{{ url('panel/vendor/bootstrap-fileupload/bootstrap-fileupload.min.js') }}"></script>
        <script src="{{ url('panel/vendor/autosize/autosize.js') }}"></script> --}}
    @endsection

@endsection