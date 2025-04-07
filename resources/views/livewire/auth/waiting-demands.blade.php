<div>
    {{-- 2 --}} {{-- doğrudan mal kabul --}}
    @if(Auth::user()->can_buy)
        <div class="row mb-3">
            <div class="col">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
                        </div>
                        <h2 class="card-title"><i class="fas fa-truck-loading"></i> Giriş talepleri</h2>
                        <p class="card-subtitle">Fatura / İrsaliye kaydı ile oluşan talepler</p>
                    </header>

                    <div class="card-body">
                        {{-- bekleyen ürün alım veya satım talepleri --}}
                        @if(Auth::user()->waitForApproval->where('type',2)->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-responsive-md table-hover table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th class="center">#</th>
                                            <th class="center">Kayıt no</th>
                                            <th>Ürün kodu</th>
                                            <th>Ürün adı</th>
                                            <th>Talebi oluşturan</th>
                                            <th>İşlem açıklaması</th>
                                            <th class="center">Hareket yönü</th>
                                            <th class="center">Miktar</th>
                                            <th class="center">Birim</th>
                                            <th class="center">Kayıt tarihi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (Auth::user()->waitForApproval->where('type',2) as $row)
                                            @if ($row->getItem)
                                                <tr>
                                                    <td class="center">
                                                        <a wire:click="confirmModal({{ $row->id }}, {{ $row->type }}, 'cancel')" href="#" class="btn btn-danger btn-xs mx-1">
                                                            <i class="fas fa-times"></i>
                                                        </a>
                                                    </td>
                                                    <td class="center">{{ $row->id }}</td>
                                                    <td>{{ $row->getItem->code }}</td>
                                                    <td>{{ $row->getItem->name }}</td>
                                                    <td>{{ $row->getSender->name }}</td>
                                                    <td>{{ $row->content }}</td>
                                                    <td class="center">
                                                        <span class="badge badge-danger"><i class="fas fa-truck-loading"></i> {{ $row->dwindling_warehouse_id != 0 ? $row->getDwindlingWarehouse->name : '' }}</span>
                                                        <i class="fas fa-angle-double-right"></i>
                                                        <span class="badge badge-success"> {{ $row->increased_warehouse_id != 0 ? $row->getIncreasedWarehouse->name : '' }}</span>
                                                    </td>
                                                    <td class="center">{{ $row->amount }}</td>
                                                    <td class="center">{{ $row->getItem->itemToUnit->content }}</td>
                                                    <td class="center">{{ $row->created_at }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </section>
            </div>
        </div>
    @endif

    {{-- 3 --}} {{-- ürün satışı --}}
    @if(Auth::user()->can_exit)
        <div class="row mb-3">
            <div class="col">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
                        </div>
                        <h2 class="card-title"><i class="fas fa-dolly-flatbed"></i> Çıkış talepleri</h2>
                        <p class="card-subtitle">Ürün satışı ile kayıt oluşturulan talepler</p>
                    </header>

                    <div class="card-body">
                        {{-- bekleyen ürün alım veya satım talepleri --}}
                        @if(Auth::user()->waitForApproval->where('type',3)->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-responsive-md table-hover table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th class="center">#</th>
                                            <th class="center">Kayıt no</th>
                                            <th>Ürün kodu</th>
                                            <th>Ürün adı</th>
                                            <th>Talebi oluşturan</th>
                                            <th>İşlem açıklaması</th>
                                            <th class="center">Hareket yönü</th>
                                            <th class="center">Miktar</th>
                                            <th class="center">Birim</th>
                                            <th class="center">Kayıt tarihi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (Auth::user()->waitForApproval->where('type',3) as $row)
                                            @if ($row->getItem)
                                                <tr>
                                                    <td class="center">
                                                        <a wire:click="confirmModal({{ $row->id }}, {{ $row->type }}, 'cancel')" href="#" class="btn btn-danger btn-xs mx-1">
                                                            <i class="fas fa-times"></i>
                                                        </a>
                                                    </td>
                                                    <td class="center">{{ $row->id }}</td>
                                                    <td>{{ $row->getItem->code }}</td>
                                                    <td>{{ $row->getItem->name }}</td>
                                                    <td>{{ $row->getSender->name }}</td>
                                                    <td>{{ $row->content }}</td>
                                                    <td class="center">
                                                        <span class="badge badge-danger"><i class="fas fa-truck-loading"></i> {{ $row->dwindling_warehouse_id != 0 ? $row->getDwindlingWarehouse->name : 'Satın Alım (Faturalı)' }}</span>
                                                        <i class="fas fa-angle-double-right"></i>
                                                        <span class="badge badge-success"> {{ $row->increased_warehouse_id != 0 ? $row->getIncreasedWarehouse->name : 'Satış' }}</span>
                                                    </td>
                                                    <td class="center">{{ $row->amount }}</td>
                                                    <td class="center">{{ $row->getItem->itemToUnit->content }}</td>
                                                    <td class="center">{{ $row->created_at }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </section>
            </div>
        </div>
    @endif

    {{-- 5 --}} {{-- depolar arası transfer --}}
    <div class="row mb-3">
        <div class="col">
            <section class="card">
                <header class="card-header">
                    <div class="card-actions">
                        <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                        <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
                    </div>
                    <h2 class="card-title"><i class="fas fa-exchange-alt"></i> Transfer talepleri</h2>
                    {{-- <h4>Depoma giriş talepleri (Diğer)</h4> --}}
                    <p class="card-subtitle">Depolar arası transfer talepleri <span class="badge badge-success">GİRİŞ</span></p>
                </header>

                <div class="card-body">
                    {{-- bekleyen deepolar arası ürün gönderme ve isteme talepleri --}}
                    @if(Auth::user()->waitForApproval->where('type',5)->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-responsive-md table-hover table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th class="center">#</th>
                                        <th class="center">Kayıt no</th>
                                        <th>Ürün kodu</th>
                                        <th>Ürün adı</th>
                                        <th>Talebi oluşturan</th>
                                        <th>İşlem açıklaması</th>
                                        <th class="center">Hareket yönü</th>
                                        <th class="center">Miktar</th>
                                        <th class="center">Birim</th>
                                        <th class="center">Kayıt tarihi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (Auth::user()->waitForApproval->where('type',5) as $row)
                                        @if ($row->getItem)
                                            <tr>
                                                <td class="center">
                                                    <a wire:click="confirmModal({{ $row->id }}, {{ $row->type }}, 'cancel')" href="#" class="btn btn-danger btn-xs mx-1">
                                                        <i class="fas fa-times"></i>
                                                    </a>
                                                </td>
                                                <td class="center">{{ $row->id }}</td>
                                                <td>{{ $row->getItem?->code }}</td>
                                                <td>{{ $row->getItem?->name }}</td>
                                                <td>{{ $row->getSender->name }}</td>
                                                <td>{{ $row->content }}</td>
                                                <td class="center">
                                                    <span class="badge badge-danger">{{ $row->getDwindlingWarehouse->name }}</span>
                                                    <i class="fas fa-angle-double-right"></i>
                                                    <span class="badge badge-success">{{ $row->getIncreasedWarehouse->name }}</span>
                                                </td>
                                                <td class="center">{{ $row->amount }}</td>
                                                <td class="center">{{ $row->getItem?->itemToUnit->content }}</td>
                                                <td class="center">{{ $row->created_at }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </section>
        </div>
    </div>

    {{-- 0 --}} {{-- depomdan istenen talepler --}}
    <div class="row mb-3">
        <div class="col">
            <section class="card">
                <header class="card-header">
                    <div class="card-actions">
                        <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                        <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
                    </div>
                    <h2 class="card-title"><i class="fas fa-exchange-alt"></i> Transfer talepleri</h2>
                    {{-- <h4>Depomdan istenilen ürünler</h4> --}}
                    <p class="card-subtitle">Depolar arası transfer talepleri <span class="badge badge-danger">ÇIKIŞ</span></p>                    
                </header>

                <div class="card-body">
                    {{-- bekleyen deepolar arası ürün gönderme ve isteme talepleri --}}
                    @if(Auth::user()->waitForApproval->where('type',0)->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-responsive-md table-hover table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th class="center">#</th>
                                        <th class="center">Kayıt no</th>
                                        <th>Ürün kodu</th>
                                        <th>Ürün adı</th>
                                        <th>Talebi oluşturan</th>
                                        <th>İşlem açıklaması</th>
                                        <th class="center">Hareket yönü</th>
                                        <th class="center">Miktar</th>
                                        <th class="center">Birim</th>
                                        <th class="center">Kayıt tarihi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (Auth::user()->waitForApproval->where('type',0) as $row)
                                        @if ($row->getItem)
                                            <tr>
                                                <td class="center">
                                                    <a wire:click="confirmModal({{ $row->id }}, {{ $row->type }}, 'cancel')" href="#" class="btn btn-danger btn-xs mx-1">
                                                        <i class="fas fa-times"></i>
                                                    </a>
                                                </td>
                                                <td class="center">{{ $row->id }}</td>
                                                <td>{{ $row->getItem->code }}</td>
                                                <td>{{ $row->getItem->name }}</td>
                                                <td>{{ $row->getSender->name }}</td>
                                                <td>{{ $row->content }}</td>
                                                <td class="center">
                                                    <span class="badge badge-danger">{{ $row->getDwindlingWarehouse?->name }}</span>
                                                    <i class="fas fa-angle-double-right"></i>
                                                    <span class="badge badge-success">{{ $row->getIncreasedWarehouse?->name }}</span>
                                                </td>
                                                <td class="center">{{ $row->amount }}</td>
                                                <td class="center">{{ $row->getItem->itemToUnit->content }}</td>
                                                <td class="center">{{ $row->created_at }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </section>
        </div>
    </div>

    {{-- 7 --}} {{-- satın alma talepleri --}}
    <div class="row mb-3">
        <div class="col">
            <section class="card">
                <header class="card-header">
                    <div class="card-actions">
                        <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                        <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
                    </div>
                    <h2 class="card-title"><i class="fas fa-exchange-alt"></i> Satın alma talepleri</h2>
                    <p class="card-subtitle">Satın alma talepleri <span class="badge badge-warning">Talep</span></p>
                </header>

                <div class="card-body">
                    {{-- bekleyen deepolar arası ürün gönderme ve isteme talepleri --}}
                    @if(Auth::user()->waitForApproval->whereIn('type',[7,9])->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-responsive-md table-hover table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th class="center">#</th>
                                        <th class="center">Kayıt no</th>
                                        <th>Ürün kodu</th>
                                        <th>Ürün adı</th>
                                        <th>Talebi oluşturan</th>
                                        <th>İşlem açıklaması</th>
                                        <th class="center">Miktar</th>
                                        <th class="center">Birim</th>
                                        <th class="center">Depo</th>
                                        <th class="center">Kayıt tarihi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (Auth::user()->waitForApproval->where('type',7) as $row)
                                        @if ($row->getItem)
                                            <tr>
                                                <td class="center">
                                                    <a wire:click="confirmModal({{ $row->id }}, {{ $row->type }}, 'cancel')" href="#" class="btn btn-danger btn-xs mx-1">
                                                        <i class="fas fa-times"></i>
                                                    </a>
                                                </td>
                                                <td class="center">{{ $row->id }}</td>
                                                <td>{{ $row->getItem->code }}</td>
                                                <td>{{ $row->getItem->name }}</td>
                                                <td>{{ $row->getSender->name }}</td>
                                                <td>{{ $row->content }}</td>
                                                <td class="center">{{ $row->amount }}</td>
                                                <td class="center">{{ $row->getItem->itemToUnit->content }}</td>
                                                <td class="center">{{ $row->getIncreasedWarehouse->name }}</td>
                                                <td class="center">{{ $row->created_at }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    @foreach (Auth::user()->waitForApproval->where('type',9) as $row)
                                        @if ($row->getItem)
                                            <tr>
                                                <td class="center">
                                                    <a wire:click="confirmModal({{ $row->id }}, {{ $row->type }}, 'cancel')" href="#" class="btn btn-danger btn-xs mx-1">
                                                        <i class="fas fa-times"></i>
                                                    </a>
                                                </td>
                                                <td class="center">{{ $row->id }}</td>
                                                <td>{{ $row->getItem->code }}</td>
                                                <td>{{ $row->getItem->name }}</td>
                                                <td>{{ $row->getSender->name }}</td>
                                                <td>{{ $row->content }}</td>
                                                <td class="center">{{ $row->amount }}</td>
                                                <td class="center">{{ $row->getItem->itemToUnit->content }}</td>
                                                <td class="center">{{ $row->getIncreasedWarehouse->name }}</td>
                                                <td class="center">{{ $row->created_at }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </section>
        </div>
    </div>

        
    
    {{-- modals --}}

    <div class="modal fade" id="{{ self::model.'confirmOrCancelModal' }}" tabindex="-1" aria-labelledby="{{ self::model.'confirmOrCancelModallabel' }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form autocomplete="off" wire:submit.prevent="confirm">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            İşlemi onaylayınız
                        </h5>
                        <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{-- @if($action == 'confirm')

                            <span class="badge badge-danger">{{ $selectedModelData->amount.' '.$selectedModelData->getItem->itemToUnit->content }}</span>
                            {{ $selectedModelData->getItem->name }}

                            @if($typeId != 3)
                                <span class="badge badge-danger">{{ $selectedModelData->getIncreasedWarehouse->name }}</span> deposuna aktarılacak ? 
                            @else
                                <span class="badge badge-danger">{{ $selectedModelData->getDwindlingWarehouse->name }}</span> deposundan satışa çıkacak ?
                            @endif

                        @else --}}
                        <div class="mb-3">
                            <div class="form-group">
                                <label class="control-label" for="textareaAutosize">İptal nedeni ?</label>
                                <textarea wire:model.defer="content_answer" class="form-control" rows="3" id="textareaAutosize" data-plugin-textarea-autosize required></textarea>
                            </div>
                        </div>
                        {{-- @endif --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('site.modal.button.cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ trans('site.modal.button.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        window.addEventListener('{{ self::model }}confirmOrCancelModalShow', event => {
            $('#{{ self::model }}confirmOrCancelModal').modal('show');
        });
        window.addEventListener('{{ self::model }}confirmOrCancelModalHide', event => {
            $('#{{ self::model }}confirmOrCancelModal').modal('hide');
        });
    </script>

    @include('parts.alert')

</div>
