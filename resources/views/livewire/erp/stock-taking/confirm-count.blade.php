<div>
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
                @if(Auth::user()->can_confirm_count)
                    <button class="btn btn-warning btn-sm" wire:click="process(0, 'confirmAll')">
                        <i class="fas fa-clipboard-check"></i>
                        Tüm ürünleri onayla ve stoğa işle
                    </button>
                    <button class="btn btn-danger btn-sm" wire:click="process(0, 'cancel')">
                        <i class="fas fa-window-close"></i>
                        Tüm ürünlerin sayımını iptal et
                    </button>
                @endif
                
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
                                                <th class="center">Mevcut stok</th>
                                                <th class="center">Sayım miktarı</th>
                                                <th>Sayımı yapan</th>
                                                <th>Durum</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $row)
                                                <tr class="{{ is_null($row->item) ? 'bg-danger':''  }}">
                                                    <td>
                                                        @if(Auth::user()->can_confirm_count)
                                                            <div class="btn-group flex-wrap">
                                                                <a style="text-decoration: none;" href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                                                                    # {{ $row->id }} 
                                                                    <span class="caret"></span>
                                                                </a>
                                                                <div class="dropdown-menu" role="menu">
                                                                    <button wire:click="process({{ $row->id }}, 'confirmByRow')" class="dropdown-item text-1">
                                                                        <i class="fas fa-check-square"></i>
                                                                        Bu kaydı onayla
                                                                    </button>
                                                                    <button wire:click="process({{ $row->id }}, 'delete')" class="dropdown-item text-1">
                                                                        {!! trans('site.button.delete') !!}
                                                                    </button>
                                                                </div>
                                                                
                                                            </div>
                                                        @else
                                                            {{ $row->id }}
                                                        @endif
                                                    </td>
                                                    <td>{{ $row->item?->code }}</td>
                                                    <td>{{ $row->item?->name }}</td>
                                                    <td>{{ $row->warehouse->code }}</td>
                                                    <td>{{ $row->warehouse->name }}</td>
                                                    <td class="center">{{ ($row->item?->stock($row->warehouse_id)) ? $row->item?->stock($row->warehouse_id)->amount : '0' }} {{ $row->item?->itemToUnit->code }}</td>
                                                    <td class="center">{{ $row->amount }} {{ $row->item?->itemToUnit->code }}</td>
                                                    <td>{{ $row->countingUser->name }}</td>
                                                    <td>{{ $row->getStatus() }}</td>
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
                        <hr>
                        
                    </div>
                </div>
            </div>
        @else
            <div class="card-body">
                Sayım verisi yok.
            </div>
        @endif
    @endif

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

    {{-- modal cancel --}}
    <div class="modal fade" id="{{ self::model.'cancelmodal' }}" tabindex="-1" aria-labelledby="{{ self::model.'cancelmodalLabel' }}"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        İşlemi onaylayınız.
                    </h5>
                    <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Listedeki tüm ürünlerin sayımları iptal edilecek ? 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('site.modal.button.cancel') }}</button>
                    <button wire:click="cancel" type="button" class="btn btn-primary">{{ trans('site.modal.button.confirm') }}</button>
                </div>
            </div>
        </div>
    </div>

    {{-- modal confirmByRow --}}
    <div class="modal fade" id="{{ self::model.'confirmByRowmodal' }}" tabindex="-1" aria-labelledby="{{ self::model.'confirmByRowmodalLabel' }}"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        İşlemi onaylayınız.
                    </h5>
                    <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Ürüne ait miktar bilgileri sayım verisi ile güncellenecek ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('site.modal.button.cancel') }}</button>
                    <button wire:click="confirmByRow" type="button" class="btn btn-primary">{{ trans('site.modal.button.confirm') }}</button>
                </div>
            </div>
        </div>
    </div>

    {{-- modal confirmAll --}}
    <div class="modal fade" id="{{ self::model.'confirmAllmodal' }}" tabindex="-1" aria-labelledby="{{ self::model.'confirmAllmodalLabel' }}"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        İşlemi onaylayınız.
                    </h5>
                    <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <strong>
                        Listedeki tüm ürünlere ait miktar bilgileri sayım verileri ile güncellenecek ?
                    </strong>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('site.modal.button.cancel') }}</button>
                    <button wire:click="confirmAll" type="button" class="btn btn-primary">{{ trans('site.modal.button.confirm') }}</button>
                </div>
            </div>
        </div>
    </div>

    <script>

        window.addEventListener('{{ self::model }}deletemodalShow', event => {
            $('#{{ self::model }}deletemodal').modal('show');
        });
        window.addEventListener('{{ self::model }}deletemodalHide', event => {
            $('#{{ self::model }}deletemodal').modal('hide');
        });
        window.addEventListener('{{ self::model }}cancelmodalShow', event => {
            $('#{{ self::model }}cancelmodal').modal('show');
        });
        window.addEventListener('{{ self::model }}cancelmodalHide', event => {
            $('#{{ self::model }}cancelmodal').modal('hide');
        });
        window.addEventListener('{{ self::model }}confirmByRowmodalShow', event => {
            $('#{{ self::model }}confirmByRowmodal').modal('show');
        });
        window.addEventListener('{{ self::model }}confirmByRowmodalHide', event => {
            $('#{{ self::model }}confirmByRowmodal').modal('hide');
        });
        window.addEventListener('{{ self::model }}confirmAllmodalShow', event => {
            $('#{{ self::model }}confirmAllmodal').modal('show');
        });
        window.addEventListener('{{ self::model }}confirmAllmodalHide', event => {
            $('#{{ self::model }}confirmAllmodal').modal('hide');
        });
    </script>

    @include('parts.alert')

</div>
