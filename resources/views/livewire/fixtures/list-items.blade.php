<div>
    <div class="row">
        <div class="col">
            <section class="card">
                <header class="card-header">
                    <div class="card-actions">
                        <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                        <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
                    </div>
                    <h2 class="card-title"></h2>
                    <p class="card-subtitle">Toplam {{ $data->total() }} kayıttan {{ $data->count() }} adet listeleniyor.</p>
                </header>

                <div class="card-body">

                    <a wire:click="process(0,'insert')" class="btn btn-primary btn-xs mb-2">
                        {!! trans('site.button.insert') !!}
                    </a>

                    <div class="col-lg-12 col-sm-12 pb-sm-3 pb-md-0 mb-2">
                        <input wire:model="search" type="search" class="form-control" placeholder="aramak istediğiniz ürünün adını ya da diğer bilgilerini buraya yazınız.">
                    </div>
                    
                    <div class="table-responsive" style="min-height: 160px;">
                        <table class="table table-bordered table-bordered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Ürün kodu</th>
                                    <th>Ürün adı</th>
                                    <th>Barcode</th>
                                    <th>Lokasyon</th>
                                    <th>Bölüm</th>
                                    <th>Kat</th>
                                    <th>Oda kodu</th>
                                    <th>Marka</th>
                                    <th class="center">Adet</th>
                                    <th class="center">Kayıt tarihi</th>
                                    <th class="center">Son değişiklik tarihi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $row)
                                    <tr>
                                        <td>
                                            <div class="btn-group flex-wrap">
                                                
                                                <a style="text-decoration: none;" href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                                                    # {{ $row->id }} 
                                                    <span class="caret"></span>
                                                </a>
                                                <div class="dropdown-menu" role="menu">
                                                    <button wire:click="process({{ $row->id }}, 'update')" class="dropdown-item">
                                                        {!! trans('site.button.update') !!}
                                                    </button>
                                                    <a href="{{ route('demirbas.download', [$row->id, 'goruntule']) }}" target="_blank" class="dropdown-item">
                                                        <i class="fa fa-file-pdf"></i> Görüntüle
                                                    </a>
                                                    <a href="{{ route('demirbas.download', [$row->id, 'indir']) }}" class="dropdown-item">
                                                        <i class="fa fa-download"></i> İndir
                                                    </a>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <button wire:click="process({{ $row->id }}, 'delete')" class="dropdown-item">
                                                        {!! trans('site.button.delete') !!}
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $row->code }}</td>
                                        <td>{{ $row->item_name }}</td>
                                        <td>{{ $row->barcode }}</td>
                                        <td>{{ $row->location }}</td>
                                        <td>{{ $row->section }}</td>
                                        <td>{{ $row->floor }}</td>
                                        <td>{{ $row->room_code }}</td>
                                        <td>{{ $row->brand }}</td>
                                        <td class="center">{{ $row->amount }}</td>
                                        <td class="center">{{ $row->updated_at->format('Y-m-d H:i') }}</td>
                                        <td class="center">{{ $row->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {!! ($data->count() < 4) ? '<br><br><br><br><br>' : '' !!}
                        <hr>
                        {{ $data->links() }}
                    </div>
                    
                </div>

            </section>
        </div>
    </div>

    {{-- modal delete --}}
    <div class="modal fade" id="{{ self::model.'deletemodal' }}" tabindex="-1" aria-labelledby="{{ self::model.'deletemodalLabel' }}"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('site.modal.header.'.$action) }}</h5>
                    <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">{!! trans('site.modal.deleteinfo') !!}</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('site.modal.button.cancel') }}</button>
                    <button wire:click="delete" type="button" class="btn btn-primary">{{ trans('site.modal.button.confirm') }}</button>
                </div>
            </div>
        </div>
    </div>

    {{-- modal insert or update --}}
    <div class="modal fade" id="{{ self::model.'upsertmodal' }}" tabindex="-1" aria-labelledby="{{ self::model.'upsertmodalLabel' }}"
        aria-hidden="true" wire:ignore.self>
        <form autocomplete="off" wire:submit.prevent="upsert">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ trans('site.modal.header.'.$action) }}</h5>
                        <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">

                        @if($action == 'insert')
                        <div class="mb-3">
                            <label class="form-label">Ürün kodu</label>
                            <input wire:model.defer="selectedArrayData.code" type="text" class="form-control @error('code') is-invalid @enderror" placeholder="Ürün kodunu giriniz">
                            @error('code')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        @endif
                        
                        <div class="mb-3">
                            <label class="form-label">Ürün barkodu</label>
                            <input wire:model.defer="selectedArrayData.barcode" type="text" class="form-control @error('barcode') is-invalid @enderror" placeholder="Ürün barkodunu giriniz.">
                            @error('barcode')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>                       
                            
                        <div class="row mb-3">
                            <div class="col-4 mb-0">
                                <label class="form-label">Ürün lokasyonu</label>
                                
                                <select wire:model.defer="selectedArrayData.location" data-plugin-selectTwo class="form-control populate @error('location') is-invalid @enderror">
                                    <option value="">Lütfen seçiniz</option>
                                    <option value="FABRİKA">FABRİKA</option>                                
                                    <option value="MERKEZ OFİS">MERKEZ OFİS</option>                                
                                </select>
                                @error('location')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="col-4 mb-0">
                                <label class="form-label">Bölüm</label>
                                <input wire:model.defer="selectedArrayData.section" type="text" class="form-control @error('section') is-invalid @enderror" placeholder="Bölüm bilgisi giriniz.">
                                @error('section')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="col-4 mb-0">
                                <label class="form-label">Kat</label>
                                <input wire:model.defer="selectedArrayData.floor" type="text" class="form-control @error('floor') is-invalid @enderror" placeholder="Kat bilgisi giriniz.">
                                @error('floor')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Oda bilgisi</label>
                            <input wire:model.defer="selectedArrayData.room_code" type="text" class="form-control @error('room_code') is-invalid @enderror" placeholder="Oda bilgisi giriniz.">
                            @error('room_code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ürün adı</label>
                            <input wire:model.defer="selectedArrayData.item_name" type="text" class="form-control @error('item_name') is-invalid @enderror" placeholder="Ürün adını giriniz">
                            @error('item_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ürün açıklaması</label>
                            <input wire:model.defer="selectedArrayData.content" type="text" class="form-control @error('content') is-invalid @enderror" placeholder="Ürün detaylı açıklama">
                            @error('content')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                       
                        <div class="row">
                            <div class="col-6 mb-0">
                                <label class="form-label">Marka</label>
                                <input wire:model.defer="selectedArrayData.brand" type="text" class="form-control @error('brand') is-invalid @enderror" placeholder="marka bilgisi giriniz">
                                @error('brand')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-6 mb-0">
                                <label class="form-label">Adet</label>
                                <input wire:model.defer="selectedArrayData.amount" type="number" min="1" class="form-control @error('amount') is-invalid @enderror" placeholder="adet bilgisi giriniz.">
                                @error('amount')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('site.modal.button.cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ trans('site.modal.button.save') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        window.addEventListener('{{ self::model }}deletemodalShow', event => {
            $('#{{ self::model }}deletemodal').modal('show');
        });
        window.addEventListener('{{ self::model }}deletemodalHide', event => {
            $('#{{ self::model }}deletemodal').modal('hide');
        });
        window.addEventListener('{{ self::model }}upsertmodalShow', event => {
            $('#{{ self::model }}upsertmodal').modal('show');
        });
        window.addEventListener('{{ self::model }}upsertmodalHide', event => {
            $('#{{ self::model }}upsertmodal').modal('hide');
        });
    </script>

    @include('parts.alert')


</div>
