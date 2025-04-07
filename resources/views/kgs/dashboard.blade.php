@section('title')
    {{ $title }}
@endsection
@section('js')
    <script src="{{ url('panel/vendor/jquery.easy-pie-chart/jquery.easypiechart.min.js') }}"></script>
@endsection

@extends('layouts.main')
@section('content')
    <!-- start: page -->
    <div class="row">
        <div class="col-12">
            <livewire:kgs.kimlik-listesi />
        </div>
        <div class="col-12">
            <livewire:kgs.haftalik />  
        </div>
    </div>
        <!-- end: page --> 
@endsection