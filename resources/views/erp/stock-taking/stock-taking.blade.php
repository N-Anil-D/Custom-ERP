@section('title')
    {{ $title }}
@endsection

@extends('layouts.main')
@section('content')
    <!-- start: page -->
    {{-- <livewire:erp.stock-taking.stock-taking />   --}}
    {{-- geçici olarak livewire düzeninden blade düzenine dönüldü. component teki frontend javascript sorunu nedeniyle. --}}

    <div class="row mb-3">
        <div class="col">
            <section class="card">

                <header class="card-header">
                    <div class="card-actions">
                        <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                        <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
                    </div>
                    <h2 class="card-title">Stok sayım işlemi</h2>
                    <p class="card-subtitle">ürün kodu / depo bilgisi / miktar girilerek kayıt sağlanır.</p>
                </header>

                <div class="card-body">

                    <form autocomplete="off" method="POST" action="{{ route('stockTaking.addStockTaking') }}">
                        @csrf

                        <div class="form-group row pb-2">    
                                <div class="col-lg-12 mb-3">
                                    <select name="warehouse_id" data-plugin-selectTwo class="form-control populate @error('warehouse_id') is-invalid @enderror" data-plugin-options='{ "allowClear": true }'>
                                        @if(Auth::user()->can_count_all)
                                            <option value="">Lütfen depo kodunu ya da adını giriniz...</option>
                                            @foreach ($warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}" @if(old('warehouse_id') == $warehouse->id) selected @endif>
                                                    {{ $warehouse->code }} - {{ $warehouse->name }} - {{ $warehouse->content }}
                                                </option>
                                            @endforeach
                                        @elseif(count(Auth::user()->warehouses) == 1)
                                            @php
                                                $warehouse = Auth::user()->warehouses->first();
                                            @endphp
                                            <option value="{{ $warehouse->warehouse->id }}" selected>
                                                {{ $warehouse->warehouse->code }} - {{ $warehouse->warehouse->name }} - {{ $warehouse->warehouse->content }}
                                            </option>
                                        @else
                                            <option value="">Lütfen depo kodunu ya da adını giriniz...</option>
                                            @foreach (Auth::user()->warehouses as $warehouse)
                                                <option value="{{ $warehouse->warehouse->id }}" @if(old('warehouse_id')==$warehouse->warehouse->id) selected @endif>
                                                    {{ $warehouse->warehouse->code }} - {{ $warehouse->warehouse->name }} - {{ $warehouse->warehouse->content }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('warehouse_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                                      
                            
                            
                            <div class="col-lg-12 mb-3">
                                <select name="item_id" data-plugin-selectTwo class="form-control populate @error('item_id') is-invalid @enderror" data-plugin-options='{ "allowClear": true, "minimumInputLength": 3 }'>
                                    <option value="">Lütfen ürün kodunu ya da adını giriniz...</option>
                                    @foreach ($items as $item)
                                        <option value="{{ $item->id }}" @if(old('item_id')==$item->id) selected @endif>{{ $item->code }} - {{ $item->name }} - (birim : {{ $item->itemToUnit->content }}) - (tür: {{ $item->getType() }})</option>
                                    @endforeach
                                </select>

                                @error('item_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                 @enderror
                            </div>


                            <div class="col-lg-12 mb-3">
                                <input name="amount" value="{{ old('amount') }}" type="number" step="0.0001" min="0.0000" class="form-control @error('amount') is-invalid @enderror" placeholder="Miktar giriniz.">
                                @error('amount')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-lg-12 mb-3">
                                <button type="submit" class="btn btn-primary">{{ trans('site.modal.button.save') }}</button>
                            </div>

                        </div>

                    </form>


                </div>
            </section>
        </div>
    </div>

    @if($data)
        @if($data->count() > 0)
            <header class="card-header">
                <div class="card-actions">
                    <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                    <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
                </div>
                <h2 class="card-title">Sayılan ürünler</h2>
            </header>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <section class="card">
                            <div class="card-body">
                                <div class="table-responsive" style="min-height: 160px;">
                                    <table class="table table-responsive-md table-hover table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Ürün kodu</th>
                                                <th>Ürün adı</th>
                                                <th>Depo kodu</th>
                                                <th>Depo adı</th>
                                                <th class="center">Sayım miktarı</th>
                                                <th>Sayımı yapan</th>
                                                <th>Durum</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $row)
                                                @if (!is_null($row->item))
                                                    <tr>
                                                        <td>
                                                            <div class="btn-group flex-wrap">
                                                                <a style="text-decoration: none;" href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                                                                    # {{ $row->id }} 
                                                                    <span class="caret"></span>
                                                                </a>
                                                                <div class="dropdown-menu" role="menu">
                                                                    <a href="{{ route('stockTaking.delete',$row->id) }}" class="dropdown-item text-1">
                                                                        {!! trans('site.button.delete') !!}
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>{{ $row->item->code }}</td>
                                                        <td>{{ $row->item->name }}</td>
                                                        <td>{{ $row->warehouse->code }}</td>
                                                        <td>{{ $row->warehouse->name }}</td>
                                                        <td class="center">{{ $row->amount }} {{ $row->item->itemToUnit->code }}</td>
                                                        <td>{{ $row->countingUser->name }}</td>
                                                        <td>{{ $row->getStatus() }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {!! ($data->count() < 4) ? '<br><br><br>' : '' !!}
                                    <hr>
                                    {{ $data->links() }}
                                </div>
                            </div>
                        </section>
                        <hr>
                        <a class="btn btn-warning btn-sm" href="{{ route('stockTaking.confirm') }}">Sayımı bitir ve onaya gönder</a>
                    </div>
                </div>
            </div>
        @endif
    @endif

    @if(Session::has('success'))
    <script>

        alertify.set('notifier','position','top-right',10);
        alertify.success("{{ Session::get('success') }}",10);

    </script>
    @endif
    @if(Session::has('error'))
    <script>

        alertify.set('notifier','position','top-right',10);
        alertify.error("{{ Session::get('error') }}",10);

    </script>
    @endif

    <!-- end: page --> 
@endsection