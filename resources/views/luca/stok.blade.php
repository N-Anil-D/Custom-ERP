@section('title')
    {{ $title }}
@endsection

@extends('layouts.main')
@section('content')
    <!-- start: page -->
    <div class="row">
        <div class="col">
            <section class="card">
                <header class="card-header">
                    <div class="card-actions">
                        <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                        <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
                    </div>
                    <h2 class="card-title">Stok listesi</h2>
                </header>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0">
                            <thead>
                                <tr>
                                    <th><i class="fa fa-search"></i></th>
                                    <th>Stok kodu</th>
                                    <th>Stok adı</th>
                                    <th>Eklenme tarihi</th>
                                    <th class="text-center">Stok Türü</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stockData as $row)
                                    {{-- {{ dd(Arr::dot($row)) }} --}}
                                    <tr>
                                        <td>
                                            <a href="{{ route('luca.stok.detay',Arr::get($row, 'kart.kartKodu')) }}" class="btn btn-primary btn-xs">
                                                <i class="fa fa-search"></i>
                                            </a>
                                        </td>
                                        <td>
                                            {{ Arr::get($row, 'kart.kartKodu') }}
                                        </td>
                                        <td>{{ Arr::get($row, 'kart.kartAdi') }}</td>
                                        <td>{{ Arr::get($row, 'kart.eklemeTarihi') }}</td>
                                        <td class="text-center">{{ Arr::get($row, 'stokTipi') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>


            </section>
        </div>
    </div>

    <!-- end: page -->
@endsection
