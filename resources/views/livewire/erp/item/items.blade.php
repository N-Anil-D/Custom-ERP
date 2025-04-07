<div>
    <div class="row">
        <div class="col">
            <section class="card">
                <header class="card-header">
                    <div class="card-actions">
                        <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                        <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
                    </div>
                    <h2 class="card-title">Ürünler</h2>
                    <p class="card-subtitle">Dikkat ! Bu sayfada yapacağınız işlemler programın genel akışını etkilemektedir.</p>
                    <p class="card-subtitle">Toplam {{ $data->total() }} kayıttan {{ $data->count() }} adet listeleniyor.</p>
                </header>

                <div class="card-body">

                    <a wire:click="process(0,'insert')" class="btn btn-primary btn-xs mb-2">
                        {!! trans('site.button.insert') !!}
                    </a>
                    <button type="button" class="btn btn-success btn-xs mb-2" wire:click="systemExports()">
                        {!! trans('site.excel.export') !!}
                    </button>

                    <div class="col-lg-12 col-sm-12 pb-sm-3 pb-md-0 mb-2">
                        <input wire:model="search" type="search" class="form-control" placeholder="aramak istediğiniz ürünün adını ya da diğer bilgilerini buraya yazınız.">
                    </div>
                    
                    <div class="table-responsive" style="min-height: 300px;">
                        <table class="table table-bordered table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Ürün kodu</th>
                                    <th>Ürün adı</th>
                                    <th>Birim</th>
                                    <th>Tipi</th>
                                    <th class="center">Toplam stok</th>
                                    <th>Ürün tipi</th>
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
                                                    {{-- <a wire:click="addBarcode({{ $row->id }})" href="#" class="dropdown-item text-1">
                                                        <i class="fa fa-plus"></i> Barkod Ekle
                                                    </a>
                                                    <li><hr class="dropdown-divider"></li> --}}
                                                    <button wire:click="process({{ $row->id }}, 'update')" class="dropdown-item text-1">
                                                        {!! trans('site.button.update') !!}
                                                    </button>
                                                    <button wire:click="process({{ $row->id }}, 'delete')" class="dropdown-item text-1">
                                                        {!! trans('site.button.delete') !!}
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $row->code }}</td>
                                        <td>{{ $row->name }}</td>
                                        <td>{{ $row->itemToUnit->content }}</td>
                                        <td>{{ $row->itemToVariety?->name }}</td>
                                        <td class="center">{{ $row->stocks->sum('amount') }}</td>
                                        <td>{{ $row->getType() }}</td>
                                        <td class="center">{{ $row->created_at->format('Y-m-d H:i') }}</td>
                                        <td class="center">{{ $row->updated_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {!! ($data->count() < 4) ? '<br><br><br>' : '' !!}
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

                        {{-- @if($action == 'insert') --}}
                        <div class="mb-3">
                            <label class="form-label">Ürün kodu</label>
                            <input wire:model.defer="selectedArrayData.code" type="text" class="form-control @error('code') is-invalid @enderror" placeholder="Ürün kodunu giriniz">
                            @error('code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        {{-- @endif --}}

                        <div class="mb-3">
                            <label class="form-label">Ürün adı</label>
                            <input wire:model.defer="selectedArrayData.name" type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Ürün adını giriniz">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Ürün açıklaması</label>
                            <input wire:model.defer="selectedArrayData.content" type="text" class="form-control @error('content') is-invalid @enderror" placeholder="Ürün açıklamasını giriniz">
                            @error('content')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- @if($action == 'insert') --}}
                        <div class="mb-3">
                            <label class="form-label">Ürün birimi</label>
                            <select data-plugin-selectTwo class="form-control populate @error('unit_id') is-invalid @enderror" wire:model.defer="selectedArrayData.unit_id">
                                <option>Lütfen seçiniz...</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->content }}</option>
                                @endforeach
                            </select>
                            @error('unit_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        

                        <div class="mb-3">
                            <label class="form-label">Ürün tipi</label>
                            
                            <select data-plugin-selectTwo class="form-control populate @error('type') is-invalid @enderror" wire:model.defer="selectedArrayData.type">
                                <option>Lütfen seçiniz...</option>                                
                                <option value="0">Hammadde</option>                                
                                <option value="1">Yarı mamül</option>                                
                                <option value="2">Ürün</option>                                
                            </select>
                            @error('type')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        {{-- @endif --}}

                        <div class="mb-3">
                            <label class="form-label">Ürün barkodu</label>
                            <input wire:model.defer="selectedArrayData.barcode" type="text" class="form-control @error('barcode') is-invalid @enderror" placeholder="Ürün barkodunu giriniz">
                            @error('barcode')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        @if($action == 'insert')
                        <div class="mb-3">
                            <label class="form-label">Ürün giriş deposu (başlangıç ve ürün dağıtım süreci için)</label>
                            
                            <select data-plugin-selectTwo class="form-control populate @error('warehouse') is-invalid @enderror" wire:model.defer="selectedArrayData.warehouse">
                                <option>Lütfen seçiniz...</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>                                
                                @endforeach
                            </select>
                            @error('warehouse')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        @endif
                        <div class="mb-3">
                            <label class="form-label">Ürün Çeşidi Seçiniz</label>
                            
                            <select data-plugin-selectTwo class="form-control populate @error('variety_id') is-invalid @enderror" wire:model.defer="selectedArrayData.variety_id">
                                <option>Lütfen seçiniz...</option>
                                @foreach($varieties as $variety)
                                    <option value="{{ $variety->id }}">{{ $variety->name }}</option>                                
                                @endforeach
                            </select>
                            @error('variety_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
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

    {{-- modal barcode --}}
    {{-- <div class="modal fade" id="{{ self::model.'barcodemodal' }}" tabindex="-1" aria-labelledby="{{ self::model.'barcodemodalLabel' }}"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Barkod modal
                    </h5>
                    <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{ $rowId }}
                    <hr>
                    @if($rowId)
                        @livewire('erp.barcodes', ['rowId' => $rowId ])
                    @endif
                    {!! trans('site.modal.deleteinfo') !!}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('site.modal.button.cancel') }}</button>
                    <button wire:click="delete" type="button" class="btn btn-primary">{{ trans('site.modal.button.confirm') }}</button>
                </div>
            </div>
        </div>
    </div> --}}

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
        // window.addEventListener('{{ self::model }}barcodemodalShow', event => {
        //     $('#{{ self::model }}barcodemodal').modal('show');
        // });
        // window.addEventListener('{{ self::model }}barcodemodalHide', event => {
        //     $('#{{ self::model }}barcodemodal').modal('hide');
        // });
    </script>

    @include('parts.alert')


</div>
