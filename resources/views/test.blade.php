@section('title')
    {{ $title = 'test' }}
@endsection

@extends('layouts.main')
@section('content')
    <!-- start: page -->
    <div>
        <h1>ERP Ürün Ekle</h1>
        <form action={{ route('import.erp.items') }} enctype="multipart/form-data" method="post">
            @csrf
            <input type="file" name="importFile" id="importFile">
            <button type="submit">ekle</button>
        </form>
    </div>
    <hr>
    <div>
        <h1>ERP Personel Ekle</h1>
        {{-- <form action={{ route('import.erp.items') }} enctype="multipart/form-data" method="post">
            @csrf
            <input type="file" name="importFile" id="importFile">
            <button type="submit">ekle</button>
        </form> --}}
    </div>
    <!-- end: page -->
@endsection
