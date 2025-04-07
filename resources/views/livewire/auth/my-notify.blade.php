<div>    
    {{-- 2 --}} {{-- Satın alım kabul --}}
    @if(Auth::user()->confirm_buy)
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

                        @if($canConfirmBuy->count() > 0)
                        <div class="table-responsive" style="min-height: 160px;">
                            <table class="table table-responsive-md table-hover table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th class="center">#</th>
                                        <th>Kayıt no</th>
                                        <th>Ürün kodu</th>
                                        <th>Ürün adı</th>
                                        <th class="center">Talebi oluşturan</th>
                                        <th class="center">Fatura / İrsaliye no.</th>
                                        <th class="center">Fatura / İrsaliye</th>
                                        <th class="center">Hareket yönü</th>
                                        <th class="center">Miktar</th>
                                        <th class="center">Birim</th>
                                        <th class="center">Kayıt tarihi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($canConfirmBuy as $row)
                                        <tr>
                                            <td class="center">
                                                <a wire:click="confirmModal({{ $row->id }}, {{ $row->type }}, 'confirm')" href="#" class="btn btn-success btn-xs mx-1">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                                <a wire:click="confirmModal({{ $row->id }}, {{ $row->type }}, 'cancel')" href="#" class="btn btn-danger btn-xs mx-1">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </td>
                                            <td class="center">{{ $row->id }}</td>
                                            <td>{{ $row->getItem->code }}</td>
                                            <td>{{ $row->getItem->name }}</td>
                                            <td class="center">{{ isset($row->getSender->name) ? $row->getSender->name : "Kullanıcı İnaktif" }}</td>
                                            <td class="center">{{ $row->content }}</td>
                                            <td class="center">
                                                @if (isset($row->file))
                                                    <a wire:click="fileDownload('{{ $row->file }}')" href="#">
                                                        <i class='fa fa-download'></i>
                                                    </a>
                                                @endif
                                            </td>
                                            <td class="center">
                                                <span class="badge badge-danger"><i class="fas fa-truck-loading"></i></span>
                                                <i class="fas fa-angle-double-right"></i>
                                                <span class="badge badge-success">{{ $row->getIncreasedWarehouse->name }}</span>
                                            </td>
                                            <td class="center">{{ $row->amount }}</td>
                                            <td class="center">{{ $row->getItem->itemToUnit->content }}</td>
                                            <td class="center">{{ $row->created_at->format('Y-m-d H:i') }}</td>
                                        </tr>
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

    {{-- 9 --}} {{-- Satın alım --}}
    @if(Auth::user()->can_buy)
        <div class="row mb-3">
            <div class="col">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
                        </div>
                        <h2 class="card-title"><i class="fas fa-truck-loading"></i>Satın Alım</h2>
                        <p class="card-subtitle">Fatura / İrsaliye kayıt oluştur</p>
                    </header>

                    <div class="card-body">

                        @if($canBuy->count() > 0)
                        <div class="table-responsive" style="min-height: 160px;">
                            <table class="table table-responsive-md table-hover table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th class="center">#</th>
                                        <th>Kayıt no</th>
                                        <th>Ürün kodu</th>
                                        <th>Ürün adı</th>
                                        <th class="center">Talebi onaylayan</th>
                                        <th class="center">Hareket yönü</th>
                                        <th class="center">Miktar & Birim</th>
                                        <th class="center">Kayıt tarihi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($canBuy as $row)
                                        <tr>
                                            <td class="center">
                                                <a wire:click="openBuyModal({{ $row->id.','.$row->item_id }})" href="#" class="btn btn-success btn-xs mx-1">
                                                    <i class="fas fa-check"></i> {{ trans('site.button.acceptbuy') }}
                                                </a>
                                                <a wire:click="confirmModal({{ $row->id }}, {{ $row->type }}, 'cancel')" href="#" class="btn btn-danger btn-xs mx-1">
                                                    <i class="fas fa-times"></i> {{ trans('site.button.deny') }}
                                                </a>
                                            </td>
                                            <td class="center">{{ $row->id }}</td>
                                            <td>{{ $row->getItem->code }}</td>
                                            <td>{{ $row->getItem->name }}</td>
                                            <td class="center">{{ isset($row->getSender->name) ? $row->getSender->name : "Kullanıcı İnaktif" }}</td>
                                            <td class="center">
                                                <span class="badge badge-danger"><i class="fas fa-truck-loading"></i></span>
                                                <i class="fas fa-angle-double-right"></i>
                                                <span class="badge badge-success">{{ $row->getIncreasedWarehouse->name }}</span>
                                            </td>
                                            <td class="center">{{ $row->amount.' '.$row->getItem->itemToUnit->content }}</td>
                                            <td class="center">{{ $row->created_at->format('Y-m-d H:i') }}</td>
                                        </tr>
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
    @if(Auth::user()->confirm_exit)
        <div class="row my-3">
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

                        @if($canExit->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-responsive-md table-hover table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th class="center">#</th>
                                        <th>Kayıt no</th>
                                        <th>LOT</th>
                                        <th>Ürün adı</th>
                                        <th class="center">Talebi oluşturan</th>
                                        <th class="center">Müşteri adı</th>
                                        <th class="center">Hareket yönü</th>
                                        <th class="center">Miktar</th>
                                        <th class="center">Birim</th>
                                        <th class="center">Kayıt tarihi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($canExit as $row)
                                    {{-- @dd($row) --}}
                                        @if (isset($row->getItem))
                                            <tr>
                                                <td class="center">
                                                    <a wire:click="confirmModal({{ $row->id }}, {{ $row->type }}, 'confirm')" href="#" class="btn btn-success btn-xs mx-1">
                                                        <i class="fas fa-check"></i>
                                                    </a>
                                                    <a wire:click="confirmModal({{ $row->id }}, {{ $row->type }}, 'cancel')" href="#" class="btn btn-danger btn-xs mx-1">
                                                        <i class="fas fa-times"></i>
                                                    </a>
                                                </td>
                                                <td class="center">{{ $row->id }}</td>
                                                <td>{{ $row->lot_no }}</td>
                                                <td>{{ $row->getItem->name }}</td>
                                                <td class="center">{{ isset($row->getSender->name) ? $row->getSender->name : "Kullanıcı İnaktif" }}</td>
                                                <td class="center">{{ $row->content }}</td>
                                                
                                                <td class="center">
                                                    <span class="badge badge-danger">{{ $row->getDwindlingWarehouse->name }}</span>
                                                    <i class="fas fa-angle-double-right"></i>
                                                    <span class="badge badge-success"><i class="fas fa-dolly-flatbed"></i></span>
                                                </td>
                                                <td class="center">{{ $row->amount }}</td>
                                                <td class="center">{{ $row->getItem->itemToUnit->content }}</td>
                                                <td class="center">{{ $row->created_at->format('Y-m-d H:i') }}</td>
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
    
    {{-- Erp Sekk ->getGeneralStatus => 3 --}} {{-- ürün paketleme --}}
    @if(Auth::user()->confirm_quality_control)
        <div class="row my-3">
            <div class="col">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
                        </div>
                        <h2 class="card-title"><i class="fas fa-dolly-flatbed"></i> Kutulanamaya hazır ürünler</h2>
                        <p class="card-subtitle">Sterilizasyon + Etiket + Kalite süreci bitmiş ürünler kutulanma için  onayınızı bekliyor.</p>
                    </header>

                    <div class="card-body">

                        @if($readyToPackege->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-responsive-md table-hover table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th class="center">#</th>
                                        <th>LOT NO</th>
                                        <th>Ürün adı</th>
                                        <th class="center">Kalite sürecini tamamlayan</th>
                                        <th class="center">Miktar</th>
                                        <th class="center">Durum</th>
                                        <th class="center">Son işlem tarihi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($readyToPackege as $row)
                                        @if (isset($row->item))
                                            <tr>
                                                <td class="center">
                                                    <a wire:click="confirmPackageModal({{ $row->id }}, 'confirm')" href="#" class="btn btn-success btn-xs mx-1">
                                                        <i class="fas fa-check"></i>
                                                    </a>
                                                    <a wire:click="confirmPackageModal({{ $row->id }}, 'cancel')" href="#" class="btn btn-danger btn-xs mx-1">
                                                        <i class="fas fa-times"></i>
                                                    </a>
                                                </td>
                                                <td>{{ $row->lot_no }}</td>
                                                <td>{{ $row->item->name }}</td>
                                                <td class="center">{{ $row?->user->name }}</td>
                                                <td class="center">{{ $row->amount.' '.$row->item->itemToUnit->content }}</td>
                                                <td class="center">{{ $row->getGeneralStatus() }}</td>
                                                <td class="center">{{ $row->updated_at->format('Y-m-d H:i') }}</td>
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
    
    {{-- depolar arası transfer --}}
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
                    @if((Auth::user()->pendingApprovals->count() + $acceptedDemandsApproval->count()) > 0)
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
                                @foreach (Auth::user()->pendingApprovals as $row)
                                    @if (isset($row->getItem))
                                        <tr>
                                            <td class="center">
                                                <a wire:click="confirmModal({{ $row->erp_approvals_id }}, {{ $row->erp_approvals_type }}, 'confirm')" href="#" class="btn btn-success btn-xs mx-1">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                                <a wire:click="confirmModal({{ $row->erp_approvals_id }}, {{ $row->erp_approvals_type }}, 'cancel')" href="#" class="btn btn-danger btn-xs mx-1">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </td>
                                            <td class="center">{{ $row->erp_approvals_id }}</td>
                                            <td>{{ $row->getItem?->code }}</td>
                                            <td>{{ $row->getItem?->name }}</td>
                                            <td>{{ isset($row->getSender->name) ? $row->getSender->name : "Kullanıcı İnaktif" }}</td>
                                            <td>{{ $row->erp_approvals_content }}</td>
                                            <td class="center">
                                                <span class="badge badge-danger">{{ $row->getDwindlingWarehouse->name }}</span>
                                                <i class="fas fa-angle-double-right"></i>
                                                <span class="badge badge-success">{{ $row->getIncreasedWarehouse->name }}</span>
                                            </td>
                                            <td class="center">{{ $row->erp_approvals_amount }}</td>
                                            <td class="center">{{ $row->getItem?->itemToUnit->content }}</td>
                                            <td class="center">{{ $row->erp_approvals_created_at }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                                @foreach ($acceptedDemandsApproval as $row)
                                    @if (isset($row->getItem))
                                        <tr>
                                            <td class="center">
                                                <a wire:click="confirmModal({{ $row->id }}, {{ $row->type }}, 'confirm')" href="#" class="btn btn-success btn-xs mx-1">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                                <a wire:click="confirmModal({{ $row->id }}, {{ $row->type }}, 'cancel')" href="#" class="btn btn-danger btn-xs mx-1">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </td>
                                            <td class="center">{{ $row->id }}</td>
                                            <td>{{ $row->getItem?->code }}</td>
                                            <td>{{ $row->getItem?->name }}</td>
                                            <td>{{ $row->getSender?->name }}</td>
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

    {{-- depomdan istenen talepler --}}
    <div class="row mb-3">
        <div class="col">
            <section class="card">
                <header class="card-header">
                    <div class="card-actions">
                        <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                        <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
                    </div>
                    <h2 class="card-title"><i class="fas fa-exchange-alt"></i> Transfer talepleri</h2>
                    <p class="card-subtitle">Depolar arası transfer talepleri <span class="badge badge-danger">ÇIKIŞ</span></p>
                </header>

                <div class="card-body">

                    @if($demandedFromMyWarehouse->count() > 0)
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
                                @foreach ($demandedFromMyWarehouse as $row)
                                    @if (isset($row->getItem))
                                        <tr>
                                            <td class="center">
                                                <a wire:click="confirmModal({{ $row->id }}, {{ $row->type }}, 'confirm')" href="#" class="btn btn-success btn-xs mx-1">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                                <a wire:click="confirmModal({{ $row->id }}, {{ $row->type }}, 'cancel')" href="#" class="btn btn-danger btn-xs mx-1">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </td>
                                            <td class="center">{{ $row->id }}</td>
                                            <td>{{ $row->getItem->code }}</td>
                                            <td>{{ $row->getItem->name }}</td>
                                            <td>{{ isset($row->getSender->name) ? $row->getSender->name : "Kullanıcı İnaktif" }}</td>
                                            <td>{{ $row->content }}</td>
                                            <td class="center">
                                                <span class="badge badge-danger">{{ $row->getDwindlingWarehouse->name }}</span>
                                                <i class="fas fa-angle-double-right"></i>
                                                <span class="badge badge-success">{{ $row->getIncreasedWarehouse->name }}</span>
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

    {{-- satın alma talepleri --}}
    @if (Auth::user()->buy_assent)
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

                        @if($wtbRequest->count() > 0)
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
                                    @foreach ($wtbRequest as $row)
                                        @if (isset($row->getItem))
                                            <tr>
                                                <td class="center">
                                                    <a wire:click="confirmModal({{ $row->id }}, {{ $row->type }}, 'confirm')" href="#" class="btn btn-success btn-xs mx-1">
                                                        <i class="fas fa-check"></i> Talebi kabul et
                                                    </a>
                                                    <a wire:click="swapRequestToAnotherUser({{ $row->id }})" href="#" class="btn btn-warning btn-xs mx-1">
                                                        <i class="fas fa-times"></i> Talebi aktar
                                                    </a>
                                                    <a wire:click="confirmModal({{ $row->id }}, {{ $row->type }}, 'cancel')" href="#" class="btn btn-danger btn-xs mx-1">
                                                        <i class="fas fa-times"></i> Talebi red et
                                                    </a>
                                                </td>
                                                <td class="center">{{ $row->id }}</td>
                                                <td>{{ $row->getItem->code }}</td>
                                                <td>{{ $row->getItem->name }}</td>
                                                <td>{{ isset($row->getSender->name) ? $row->getSender->name : "Kullanıcı İnaktif" }}</td>
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
    @endif
    

    {{-- confirmOrCancelModal --}}
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
                        @if($action == 'confirm')
                            <div class="mb-3">
                                <u class="text-xs">{{ $selectedModelData->getItem->name }}</u>
                                <br>
                                MİKTAR : <span class="badge badge-success ml-2">{{ $selectedModelData->amount.' '.$selectedModelData->getItem->itemToUnit->content }}</span>
                            </div>
                            @if ($typeId == 5)
                                <div class="input-group mb-3">
                                    <input wire:model.defer="modalData.amount" type="number" min="0" step="0.0001" max="{{ $selectedModelData->amount }}" class="form-control @error('amount') is-invalid @enderror" required>
                                    <span class="input-group-text">{{ $selectedModelData->getItem->itemToUnit->content }}</span>
                                </div>
                            @endif

                            @if ($typeId == 7)
                                <p class="mb-3">Talebi onayladıktan sonra talep satın alma sorumlusuna iletilecektir ve talep sahibine bilgilendirme mesajı gönderilececktir. </p>
                                <h5>Arttırılacak depo</h5>
                                <select data-plugin-selectTwo class="form-control populate" wire:model.defer="changeIncreasingWarehouse.id">
                                    @foreach($buyToThisWarehouses as $buyToThisWarehouse)
                                        <option value="{{ $buyToThisWarehouse->id }}">{{ $buyToThisWarehouse->name }}</option>
                                    @endforeach
                                </select>

                            @else
                                @if($typeId != 3)
                                    <br>
                                    <span class="badge badge-danger">{{ $selectedModelData->getIncreasedWarehouse->name }}</span> deposuna aktarılacak ? 
                                @else
                                    <br>
                                    <span class="badge badge-danger">{{ $selectedModelData->getDwindlingWarehouse->name }}</span> deposundan satışa çıkarılıyor
                                    <br>
                                    <p>ONAYLIYOR MUSUNUZ ? </p>
                                @endif
                            @endif

                        @else
                        <div class="mb-3">
                            <div class="form-group">
                                <label class="control-label" for="textareaAutosize">İptal nedeni ?</label>
                                <textarea wire:model.defer="content_answer" class="form-control" rows="3" id="textareaAutosize" data-plugin-textarea-autosize required></textarea>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('site.modal.button.cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ trans('site.modal.button.confirm') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- buy modal --}}
    @if (isset($buyRecord))
        <div class="modal fade" id="{{ self::model.'openbuymodal' }}" tabindex="-1" aria-labelledby="{{ self::model.'openbuymodalLabel' }}" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-lg">
                <form autocomplete="off" wire:submit.prevent="buyRecord">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="fas fa-truck-loading"></i>  {{ ($item) ? $item->code.' - '. $item->name : '' }}</h5>
                            <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Fatura No</label>
                                <input wire:model.defer="buyRecord.content" type="text" class="form-control @error('content') is-invalid @enderror" placeholder="Fatura yada irsaliye no giriniz...">
                                @error('content')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>


                            <div class="mb-3">
                                <label for="formFile" class="form-label">Fatura görseli (Pdf yada Jpeg)</label>
                                <input wire:model.defer="buyRecord.file" class="form-control @error('file') is-invalid @enderror" type="file" id="formFile" accept=".jpg, .jpeg, .pdf">
                                    @error('file')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Satın alınan miktar (ürünün kendi biriminden değeri) {{ ($item) ? '['.$item->itemToUnit->content.']' : '' }}</label>
                                @if (isset($item))
                                <div class="input-group">
                                    <input wire:model.defer="buyRecord.amount" type="number" step="0.0001" min="0" class="form-control @error('amount') is-invalid @enderror"
                                    placeholder="Satın alınan miktarı giriniz (ürünün kendi biriminden değerini giriniz...) {{ ($item) ? '['.$item->itemToUnit->content.']' : '' }}">
                                    <span class="input-group-text">{{ $item->itemToUnit->content }}</span>
                                </div>
                                @endif
                                @error('amount')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Giriş yapılan depo</label>
                                <select data-plugin-selectTwo class="form-control populate @error('increased_warehouse_id') is-invalid @enderror" wire:model.defer="buyRecord.increased_warehouse_id">
                                    <option value="0" selected>Lütfen seçiniz...</option>
                                    @foreach($buyToThisWarehouses as $buyToThisWarehouse)
                                        <option value="{{ $buyToThisWarehouse->id }}">{{ $buyToThisWarehouse->name }}</option>
                                    @endforeach
                                </select>
                                @error('increased_warehouse_id')
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
                </form>
            </div>
        </div>
    @endif

    {{-- kutulanma onayı modal --}}
    @if (isset($selectedSekkModalData))
        <div class="modal fade" id="{{ self::model.'packageCofirmModal' }}" tabindex="-1" aria-labelledby="{{ self::model.'packageCofirmModalLabel' }}" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-lg">
                <form autocomplete="off" wire:submit.prevent="packageProccess">
                    <div class="modal-content">
                        <div class="modal-header">
                            @if ($sekkProcedure == 'confirm')
                                <h4 class="modal-title"><i class="fas fa-boxes"></i> Ürün paketlemeye gönderilecek</h4>
                            @elseif ($sekkProcedure == 'cancel')
                                <h4 class="modal-title"><i class="fas fa-vial"></i> Ürün kalite kontrol sürecine iade edilecek</h4>
                            @endif
                            <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <p>{{ $selectedSekkModalData?->item->name .' | '. $selectedSekkModalData->amount .' '. $selectedSekkModalData?->item?->itemToUnit->content }}</p>
                            </div>
                            <div class="mb-3">
                                <div class="form-group">
                                    <label class="control-label">Açıklama</label>
                                    <textarea wire:model.defer="content_answer" class="form-control" rows="3" data-plugin-textarea-autosize required></textarea>
                                </div>
                            </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('site.modal.button.cancel') }}</button>
                            <button type="submit" class="btn btn-primary">{{ trans('site.modal.button.save') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if (isset($swapRequestAbleUsers))
        <div class="modal fade" id="{{ self::model.'swapRequestmodal' }}" tabindex="-1" aria-labelledby="{{ self::model.'swapRequestmodalLabel' }}" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-lg">
                <form autocomplete="off" wire:submit.prevent="swapRequestToAnother">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="fas fa-truck-loading"></i> Satın alma talebi kime aktarılacak ?</h5>
                            <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            
                            <div class="mb-3">
                                <label class="form-label">Satın alma talebini onaylama yetkisindeki kullanıcılar</label>
                                <select data-plugin-selectTwo class="form-control populate @error('swap_to_this_user') is-invalid @enderror" wire:model.defer="swapRequest.swap_to_this_user">
                                    <option value="" selected>Lütfen seçiniz...</option>
                                    @foreach($swapRequestAbleUsers as $swapRequestAbleUser)
                                        <option value="{{ $swapRequestAbleUser->id }}">{{ $swapRequestAbleUser->name }}</option>
                                    @endforeach
                                </select>
                                @error('swap_to_this_user')
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
                </form>
            </div>
        </div>
    @endif

    <script>
        window.addEventListener('{{ self::model }}confirmOrCancelModalShow', event => {
            $('#{{ self::model }}confirmOrCancelModal').modal('show');
        });
        window.addEventListener('{{ self::model }}confirmOrCancelModalHide', event => {
            $('#{{ self::model }}confirmOrCancelModal').modal('hide');
        });
        
        window.addEventListener('{{ self::model }}openbuymodalShow', event => {
            $('#{{ self::model }}openbuymodal').modal('show');
        });
        window.addEventListener('{{ self::model }}openbuymodalHide', event => {
            $('#{{ self::model }}openbuymodal').modal('hide');
        });
        
        window.addEventListener('{{ self::model }}swapRequestmodalShow', event => {
            $('#{{ self::model }}swapRequestmodal').modal('show');
        });
        window.addEventListener('{{ self::model }}swapRequestmodalHide', event => {
            $('#{{ self::model }}swapRequestmodal').modal('hide');
        });
        
        window.addEventListener('{{ self::model }}openPackageCofirmModalShow', event => {
            $('#{{ self::model }}packageCofirmModal').modal('show');
        });
        window.addEventListener('{{ self::model }}openPackageCofirmModalHide', event => {
            $('#{{ self::model }}packageCofirmModal').modal('hide');
        });

    </script>

    @include('parts.alert')

</div>
