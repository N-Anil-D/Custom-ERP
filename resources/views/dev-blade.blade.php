@section('title')
    {{ $title = 'DEV' }}
@endsection

@extends('layouts.main')
@section('content')
    <!-- start: page -->
    <div>
        <h1>ERP Ürün Ekle</h1>
        <form action={{ route('import.erp.items') }} enctype="multipart/form-data" method="post">
            @csrf
            <input type="file" name="importFile">
            <button type="submit">EKLE</button>
        </form>
    </div>
    <hr>
    <div>
        <h1>Deponun stoğunu değiştir</h1>
        <form action={{ route('import.erp.items.to.warehouse') }} enctype="multipart/form-data" method="post">
            @csrf
            <input type="file" name="importFile">
            <button type="submit">EKLE</button>
        </form>
    </div>
    <hr>
    <div>
        <h1>Depo Ürünlerine Raf ekle</h1>
        <h4>Sadece INVAlogistic ve Yarı muamül depo</h4>
        <form action={{ route('import.erp.item.location') }} enctype="multipart/form-data" method="post">
            @csrf
            <input type="file" name="importFile">
            <button type="submit">EKLE</button>
        </form>
    </div>
    <hr>
    <div>
        <h1>Bitmiş Ürün ekle</h1>
        <form action={{ route('import.erp.finished.product') }} enctype="multipart/form-data" method="post">
            @csrf
            <input type="file" name="importFile">
            <button type="submit">EKLE</button>
        </form>
    </div>
    <hr>
    {{-- <div>
        <h1>ERP Personel Ekle</h1>
        <form action={{ route('import.erp.items') }} enctype="multipart/form-data" method="post">
            @csrf
            <input type="file" name="importFile" id="importFile">
            <button type="submit">ekle</button>
        </form>
    </div> --}}
    <!-- end: page -->
@endsection
