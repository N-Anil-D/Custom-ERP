<div>
    <div class="row">
        <div class="col">
            <section class="card">
                <header class="card-header">
                    <div class="card-actions">
                        <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                        <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
                    </div>
                    <h2 class="card-title">Depolar</h2>
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
                        <input wire:model="search" type="search" class="form-control" placeholder="aramak istediğiniz deponun adını ya da diğer bilgilerini buraya yazınız.">
                    </div>
                    
                    <div class="table-responsive" style="min-height: 300px;">
                        <table class="table table-bordered table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Depo kodu</th>
                                    <th>Depo adı</th>
                                    <th>Açıklama</th>
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
                                        <td>{{ $row->content }}</td>
                                        <td class="center">{{ $row->updated_at->format('Y-m-d H:i') }}</td>
                                        <td class="center">{{ $row->created_at->format('Y-m-d H:i') }}</td>
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

                        @if($action == 'insert')
                        <div class="mb-3">
                            <label class="form-label">Depo kodu</label>
                            <input wire:model.defer="selectedArrayData.code" type="text" class="form-control @error('code') is-invalid @enderror" placeholder="Depo kodunu giriniz">
                            @error('code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label" for="warehouseName">Depo adı</label>
                            <input wire:model.defer="selectedArrayData.name"  id="warehouseName" type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Depo adını giriniz">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label" for="explanation">Depo açıklaması</label>
                            <input wire:model.defer="selectedArrayData.content" id="explanation" type="text" class="form-control @error('content') is-invalid @enderror" placeholder="Depo açıklamasını giriniz">
                            @error('content')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Depo açıklaması</label>
                            <select data-plugin-selectTwo class="form-control populate @error('can_take_from_outside') is-invalid @enderror" wire:model.defer="selectedArrayData.can_take_from_outside">
                                <option value="0" selected>Dışarıdan Ürün girişi yapılamaz (0)</option>
                                <option value="1">Dışarıdan Ürün girişi yapılabilir (1)</option>
                            </select>
                            @error('can_take_from_outside')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Bitmiş ürün çıkışı yapabilir</label>
                            <select data-plugin-selectTwo class="form-control populate @error('can_send_to_outside') is-invalid @enderror" wire:model.defer="selectedArrayData.can_send_to_outside">
                                <option value="0" selected>Dışarıya ürün gönderemez (0)</option>
                                <option value="1">Dışarıya ürün gönderebilir (1)</option>
                            </select>
                            @error('can_send_to_outside')
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
