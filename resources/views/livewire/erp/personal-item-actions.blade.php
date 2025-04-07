<div>
    <div class="row">
        <div class="col">
            <section class="card">
                <header class="card-header">
                    <div class="card-actions">
                        <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                        <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
                    </div>
                    <p class="card-subtitle">Toplam {{ $data->total() }} kayıttan {{ $data->count() }} adet listeleniyor.</p>
                </header>
                
                <div class="card-body">
                    
                    <button type="button" class="btn btn-success btn-xs mb-2" wire:click="itemsExports()">
                        {!! trans('site.excel.export') !!}
                    </button>
                    
                    <div class="col-lg-12 col-sm-12 pb-sm-3 pb-md-0 mb-2">
                        <input wire:model="search" type="search" class="form-control" placeholder="Aramak istediğiniz ürünün adını ya da diğer bilgilerini buraya yazınız.">
                    </div>
                    
                    <div class="table-responsive" style="min-height: 300px;">
                        <table class="table table-responsive-md table-hover table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th wire:click="sort('id')"><div class="d-flex justify-content-between"><span>#</span> <span><i class="fas fa-sort-down fa-lg{{ $orderColumn == 'id' ? ' text-primary' : ' fa-rotate-180'}}"></i></span></div></th>
                                    <th wire:click="sort('code')"><div class="d-flex justify-content-between"><span>Ürün kodu</span> <span><i class="fas fa-sort-down fa-lg{{ $orderColumn == 'code' ? ' text-primary' : ' fa-rotate-180'}}"></i></span></div></th>
                                    <th wire:click="sort('name')"><div class="d-flex justify-content-between"><span>Ürün adı</span> <span><i class="fas fa-sort-down fa-lg{{ $orderColumn == 'name' ? ' text-primary' : ' fa-rotate-180'}}"></i></span></div></th>
                                    <th>Birim</th>
                                    <th class="center">Toplam stok</th>
                                    <th>
                                        <div class="btn-group flex-wrap">
                                            <span href="#" class="dropdown-toggle m-0" data-bs-toggle="dropdown">Ürün tipi </span>
                                            <div class="dropdown-menu px-3 font-weight-normal" role="menu" style="min-width:200px">
                                                <div class="checkbox-custom checkbox-primary px-3">
                                                    <li class=""><input type="checkbox" wire:model="itemTypeFilter.hm" id="cbHM"><label for="cbHM" class="text-dark px-2">Ham madde</label></li>
                                                </div>
                                                <div class="checkbox-custom checkbox-primary px-3">
                                                    <li class=""><input type="checkbox" wire:model="itemTypeFilter.ym" id="cbYM"><label for="cbYM" class="text-dark px-2">Yarı muamül</label></li>
                                                </div>
                                                <div class="checkbox-custom checkbox-primary px-3">
                                                    <li class=""><input type="checkbox" wire:model="itemTypeFilter.tm" id="cbTM"><label for="cbTM" class="text-dark px-2">Ürün</label></li>
                                                </div>
                                            </div>
                                        </div>
                                    </th>
                                    <th class="center">Son hareket tarihi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $row)
                                    <tr>
                                        <td class="center">
                                            @if (count(Auth::user()->myErpItemAlerts->where('item_id',$row->id)->where('warned',FALSE)))
                                            <a href="#" wire:click="showCancelAlert({{ $row->id }})">
                                                <i style="color:orange" class='bx bxs-bell-off bx-xs'></i>
                                            </a>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group flex-wrap">
                                                <a style="text-decoration: none;" href="#" class="dropdown-toggle" data-bs-toggle="dropdown">{{ $row->id }} <span class="caret"></span></a>
                                                <div class="dropdown-menu" role="menu">
                                                    @if(Auth::user()->can_buy && $row->type == 0)
													    <button wire:click="openBuyModal({{ $row->id }})" class="dropdown-item text-1">
                                                            <i class="fas fa-truck-loading"></i>
                                                            Satın alım(Yeni Giriş)
                                                        </button>
                                                    @endif
                                                    <button wire:click="process({{ $row->id }}, 'demand')" class="dropdown-item text-1">
                                                        <i style="color:green" class="fas fa-bullhorn bx-xs"></i>
                                                        Talep Et
                                                    </button>
                                                    @if (count(Auth::user()->myErpItemAlerts->where('item_id',$row->id)->where('warned',FALSE))==0)
                                                    <button wire:click="process({{ $row->id }}, 'alertMe')" class="dropdown-item text-1">
                                                        <i style="color:orange" class='bx bxs-bell-ring bx-tada bx-xs' ></i>
                                                        Beni uyar !
                                                    </button>
                                                    @endif
                                                    <button wire:click="process({{ $row->id }}, 'movement')" class="dropdown-item text-1">
                                                        <i class="fas fa-people-carry"></i>
                                                        Stok hareketlerini görüntüle
                                                    </button>

                                                    <button wire:click="process({{ $row->id }}, 'warehouse')" class="dropdown-item text-1">
                                                        <i class="fas fa-industry"></i>
                                                        Depolardaki dağılımın miktarını görüntüle
                                                    </button>
                                                    @if ($row->type == 0)
                                                        <button  wire:click="process({{ $row->id }}, 'wtb')" class="dropdown-item text-1">
                                                            <i class="fas fa-shopping-cart"></i>
                                                            Satın alma talebi
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $row->code }}</td>
                                        <td>{{ $row->name }}</td>
                                        <td>{{ $row->itemToUnit->content }}</td>
                                        <td class="center">{{ $row->stocks->sum('amount') }}</td>
                                        <td>{{ $row->getType() }}</td>
                                        <td class="center">{{ ($row->movements->count() > 0) ? $row->movements->max('created_at')->format('Y-m-d H:i') : '' }}</td>
                                        
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

    {{-- modal set alert --}}
    @if (isset($setAlertData))
        <div class="modal fade" id="{{ self::model }}AlertModal" tabindex="-1" aria-labelledby="{{ self::model }}AlertModalLabel" aria-hidden="true" wire:ignore.self>
            <form autocomplete="off" wire:submit.prevent="setAlert">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title">Ürün Bilgilendirme Alarmı Kur</h3>
                            <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>
                                <strong>{{ $setAlertData->code }} - {{ $setAlertData->name }} <span style="font-weight:300;">({{ $setAlertData->getType() }})</span> <br> ürünü için uyarı ver.</strong>
                                <p>{{ $setAlertData->content }}</p>
                                <span>Mevcut stok : {{ $setAlertData->stocks->sum('amount') }} {{ $setAlertData->itemToUnit->content }}</span>
                            </p>
                            <div class="mb-3">
                                <input wire:model.defer="alertArray.amount" required type="number" step="0.0001" min="0" class="form-control @error('amount') is-invalid @enderror" placeholder="{{ ucfirst($setAlertData->itemToUnit->content) }} Giriniz">
                                @error('amount')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <select wire:model="alertArray.alertCondition" class="form-control" aria-label="Default select">
                                <option value='<'>Altına düştüğünde</option>
                                <option value='>'>Üzerine çıktığında</option>
                            </select>
                            <select wire:model="alertArray.perma" class="form-control my-3" aria-label="Default select">
                                <option value='0'>Tek Seferlik</option>
                                <option value='1'>Kalıcı</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('site.modal.button.cancel') }}</button>
                            <button type="submit" class="btn btn-primary">{{ trans('site.modal.button.confirm') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endif

    {{-- modal alert cancel --}}
    <div class="modal fade" id="{{ self::model }}alertCancelModal" tabindex="-1" aria-labelledby="{{ self::model }}alertCancelModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('site.modal.header.'.$action) }}</h5>
                    <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <strong>
                        Ürüne ait bildirim kaldırılacak. 
                    </strong>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('site.modal.button.cancel') }}</button>
                    <button wire:click="cancelPersonalAlert()" type="button" class="btn btn-primary">Onayla</button>
                </div>
            </div>
        </div>
    </div>

    {{-- modal demand --}}
    @if (isset($setDemandData))
        <div class="modal fade" id="{{ self::model }}demandItemModal" tabindex="-1" aria-labelledby="{{ self::model }}demandItemModalLabel" aria-hidden="true">
            <form autocomplete="off" wire:submit.prevent="demandItem()">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Materyel Talep Et</h5>
                            <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>
                                {{ $setDemandData->code }} - {{ $setDemandData->name }} <span style="font-weight:300;">({{ $setDemandData->getType() }})</span><br>
                                <strong>Mevcut stok : {{ $setDemandData->stocks->sum('amount') }} {{ $setDemandData->itemToUnit->content }}</strong>
                                {{-- <p>{{ $setDemandData->content }}</p> --}}
                            </p>
                            <div class="mb-3">
                                <label class="form-label">Talep edilen miktar (ürünün kendi biriminden değeri) [{{ $setDemandData->itemToUnit->content }}]</label>
                                <input wire:model.defer="transferRequest.amount" type="number" min="0" step="0.0001" class="form-control @error('amount') is-invalid @enderror"
                                    placeholder="[{{ $setDemandData->itemToUnit->content }}]" required>
                                @error('amount')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Ürünün çıkış yapacağı depo</label>
                                <select data-plugin-selectTwo class="form-control populate @error('itemDemandedFromThisWarehouse') is-invalid @enderror" wire:model.defer="transferRequest.itemDemandedFromThisWarehouse" required>
                                    <option value="" selected>Ürünün çıkış yapacağı depo</option>
                                    
                                    @foreach($transferAbleWarehouses as $warehouse)
                                        @if ($warehouse->warehouse)
                                            <option value="{{ $warehouse->warehouse_id }}">{{ $warehouse->warehouse->name.' - ['.$warehouse->amount.' '.$warehouse->item->itemToUnit->code.']' }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('itemDemandedFromThisWarehouse')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Transfer edilecek depo</label>
                                <select data-plugin-selectTwo class="form-control populate @error('itemDemandedToThisWarehouse') is-invalid @enderror" wire:model.defer="transferRequest.itemDemandedToThisWarehouse" required>
                                    <option value="" selected>Depolarım</option>
                                    @foreach(Auth::user()->warehouses as $warehouse)
                                        <option value="{{ $warehouse->warehouse_id }}">{{ $warehouse->warehouse->name }}</option>
                                    @endforeach
                                </select>
                                @error('itemDemandedToThisWarehouse')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="form-group">
                                    <label class="control-label" for="textareaAutosize">İşlem açıklaması</label>
                                    <textarea wire:model.defer="transferRequest.content" class="form-control" rows="2" id="textareaAutosize" data-plugin-textarea-autosize></textarea>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('site.modal.button.cancel') }}</button>
                            <button type="submit" class="btn btn-primary">{{ trans('site.modal.button.confirm') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endif

    {{-- modal movements --}}
    <div class="modal fade" id="{{ self::model.'movementmodal' }}" tabindex="-1" aria-labelledby="{{ self::model.'movementmodalLabel' }}"
        aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content">
                    <div class="modal-header">
                        @if($movements)
                            @if($movements->count() > 0)
                                <div class="mx-2 btn-group flex-wrap">
                                    <div class="btn-group flex-wrap">
                                        <button type="button" class="btn btn-success btn-xs dropdown-toggle" data-bs-toggle="dropdown">
                                            {!! trans('site.excel.export') !!} <span class="caret"></span>
                                        </button>
                                        <div class="dropdown-menu" role="menu">
                                            <a class="dropdown-item text-1" href="#" wire:click="exportStockMovements(1)">Son 1 Ay</a>
                                            <a class="dropdown-item text-1" href="#" wire:click="exportStockMovements(3)">Son 3 Ay</a>
                                            <a class="dropdown-item text-1" href="#" wire:click="exportStockMovements(6)">Son 6 Ay</a>
                                            <a class="dropdown-item text-1" href="#" wire:click="exportStockMovements(12)">Son 12 Ay</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                        <h5 class="card-title">{{ ($item) ? $item->code.' - '.$item->name.' - Toplam stok : '.$item->stocks->sum('amount'). $item->itemToUnit->code : '' }}</h5>
                        <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">

                        @if($movements)
                            @if($movements->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-responsive-md table-hover table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th class="center">Hareket tipi</th>
                                            <th class="center">Azalan depo</th>
                                            <th class="center">Artan depo</th>
                                            <th class="center">Miktar</th>
                                            <th class="center">Talep sahibi</th>
                                            <th class="center">Onaylayan</th>
                                            <th class="center">İşlem tarihi</th>
                                            <th class="center">İşlem öncesi depodaki stok</th>
                                            <th class="center">İşlem öncesi toplam stok</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($movements as $move)
                                            <tr>
                                                <td class="center">{{ $move->getType() }}</td>
                                                @if($move->type == 4)
                                                    <td class="center" colspan="2">{{ $move->getDwindlingWarehouse->code }}</td>
                                                @else
                                                    <td class="center">{{ ($move->getDwindlingWarehouse) ? $move->getDwindlingWarehouse->code : '' }}</td>
                                                    <td class="center">{{ ($move->getIncreasedWarehouse) ? $move->getIncreasedWarehouse->code : '' }}</td>
                                                @endif
                                                @if ($move->type == 1 && ($move->increased_warehouse_id > 0 && $move->dwindling_warehouse_id == 0))
                                                    <td class="center"><span class="badge badge-success w-100">{{ $move->amount.' '.$move->item->itemToUnit->code }}</span></td>
                                                @elseif($move->type == 1 && ($move->dwindling_warehouse_id > 0 && $move->increased_warehouse_id == 0))
                                                    <td class="center"><span class="badge badge-danger w-100">{{ $move->amount.' '.$move->item->itemToUnit->code }}</span></td>
                                                @elseif($move->type == 2)
                                                    <td class="center"><span class="badge badge-success w-100">{{ $move->amount.' '.$move->item->itemToUnit->code }}</span></td>
                                                @elseif($move->type == 3 ||$move->type == 12 ||$move->type == 13)
                                                    <td class="center"><span class="badge badge-danger w-100">{{ $move->amount.' '.$move->item->itemToUnit->code }}</span></td>
                                                @elseif($move->type == 4)
                                                    <td class="center"><span class="badge badge-primary w-100">{{ $move->amount.' '.$move->item->itemToUnit->code }}</span></td>
                                                @elseif($move->type == 11)
                                                    @if ($move->getDwindlingWarehouse)
                                                        <td class="center"><span class="badge badge-danger w-100">{{ $move->amount.' '.$move->item->itemToUnit->code }}</span></td>
                                                    @else
                                                        <td class="center"><span class="badge badge-success w-100">{{ $move->amount.' '.$move->item->itemToUnit->code }}</span></td>
                                                    @endif
                                                @else
                                                    <td class="center">{{ $move->amount.' '.$move->item->itemToUnit->code }}</td>
                                                @endif
                                                @if($move->type == 1)
                                                    <td class="center" colspan="2">{{ $move->getSender->name }}</td>
                                                @else
                                                    <td class="center">{{ $move->getSender->name }}</td>
                                                    <td class="center">{{ $move->getApproval->name }}</td>
                                                @endif
                                                <td class="center">{{ $move->created_at->format('Y-m-d H:i') }}</td>
                                                <td class="center">{{ (in_array($move->type, [1,2,3,4,11,12,13])) ? $move->old_warehouse_amount.' '.$move->item->itemToUnit->code : '' }}</td>
                                                <td class="center">{{ (in_array($move->type, [1,2,3,4,11,12,13])) ? $move->old_total_amount.' '.$move->item->itemToUnit->code : '' }}</td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <p class="center">Ürün hareketi henüz yok.</p>
                            @endif
                        @endif

                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
    </div>

    {{-- modal warehouse --}}
    <div class="modal fade" id="{{ self::model.'warehousemodal' }}" tabindex="-1" aria-labelledby="{{ self::model.'warehousemodalLabel' }}"
        aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="mx-2 btn-group flex-wrap">
                            <div class="btn-group flex-wrap">
                                <button type="button" class="btn btn-success btn-xs" wire:click="exportStockDispersion()">
                                    {!! trans('site.excel.export') !!}
                                </button>
                            </div>
                        </div>
                        <h5 class="card-title">{{ ($item) ? $item->code.' - '.$item->name : '' }}</h5>
                        <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">

                        @if($item)
                            @if($item->stocks->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-responsive-md table-hover table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th class="center">Depo kodu</th>
                                            <th>Depo adı</th>
                                            <th class="center">Miktar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($item->stocks as $stock)
                                            @if (!is_null($stock->warehouse))
                                                <tr>
                                                    <td class="center">{{ $stock->warehouse?->code }}</td>
                                                    <td>{{ $stock->warehouse?->name }}</td>
                                                    <td class="center">{{ $stock->amount }} {{ $stock->item->itemToUnit->code }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <p class="center">Ürün hareketi henüz yok.</p>
                            @endif
                        @endif

                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
    </div>

    {{-- modal wtb --}}
    @if ($action == 'wtb')
        <div class="modal fade" id="{{ self::model }}WTBmodal" tabindex="-1" aria-labelledby="{{ self::model }}WTBmodalLabel" aria-hidden="true">
            <form autocomplete="off" wire:submit.prevent="wtbRequest()">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div>
                                <h5 class="modal-title">Hammadde Satın alma talebi</h5>
                                <h6 class="modal-title">{{ $item->name.' & '.$item->code }}</h6>
                            </div>
                            <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Talep edilen miktar</label>
                                <div class="input-group mb-3">
                                    <input wire:model.defer="wtbRequest.amount" type="number" min="0" step="0.0001" class="form-control @error('amount') is-invalid @enderror"
                                    placeholder="[{{ $item->itemToUnit->content }}]" required>
                                    <span class="input-group-text">{{ $item->itemToUnit->content }}</span>
                                </div>
                                @error('amount')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Satın alma talebinin gideceği kişi</label>
                                <select data-plugin-selectTwo class="form-control populate @error('wtbFromUsers') is-invalid @enderror" wire:model.defer="wtbRequest.wtbFromUser">
                                    <option value="" selected>Lütfen seçiniz...</option>
                                    @foreach($wtbFromUsers as $wtbFromUser)
                                        <option value="{{ $wtbFromUser->id }}">{{ $wtbFromUser->name }}</option>
                                    @endforeach
                                </select>
                                @error('wtbFromUsers')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
    
                            <div class="mb-3">
                                <label class="form-label">Alınan miktarın eklenmesini istediğiniz depoyu & odayı seçiniz</label>
                                <select data-plugin-selectTwo class="form-control populate @error('wtbToThisWarehouse') is-invalid @enderror" wire:model.defer="wtbRequest.wtbToThisWarehouse">
                                    <option value="" selected>Lütfen seçiniz...</option>
                                    @foreach($buyToThisWarehouses as $buyToThisWarehouse)
                                        <option value="{{ $buyToThisWarehouse->id }}">{{ $buyToThisWarehouse->name }}</option>
                                    @endforeach
                                </select>
                                @error('wtbToThisWarehouse')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
    
                            <div class="mb-3">
                                <div class="form-group">
                                    <label class="control-label" for="textareaAutosize">Talep açıklaması</label>
                                    <textarea wire:model.defer="wtbRequest.note" class="form-control" rows="2" id="textareaAutosize" data-plugin-textarea-autosize required></textarea>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('site.modal.button.cancel') }}</button>
                            <button type="submit" class="btn btn-primary">{{ trans('site.modal.button.confirm') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endif

    {{-- buy modal --}}
    <div class="modal fade" id="{{ self::model.'openbuymodal' }}" tabindex="-1" aria-labelledby="{{ self::model.'openbuymodalLabel' }}" aria-hidden="true" wire:ignore.self>
        <form autocomplete="off" wire:submit.prevent="openBuyRequest">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-truck-loading"></i>  {{ ($item) ? $item->code.' - '. $item->name : '' }}</h5>
                        <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Fatura No</label>
                            <input wire:model.defer="buyRequest.content" type="text" class="form-control @error('content') is-invalid @enderror" placeholder="Fatura yada irsaliye no giriniz...">
                            @error('content')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>


                        <div class="mb-3">
                            <label for="formFile" class="form-label">Fatura görseli (Pdf yada Jpeg)</label>
                            <input wire:model.defer="buyRequest.file" class="form-control @error('file') is-invalid @enderror" type="file" id="formFile" accept=".jpg, .jpeg, .pdf">
                                @error('file')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Sipariş miktarı (ürünün kendi biriminden değeri) {{ ($item) ? '['.$item->itemToUnit->content.']' : '' }}</label>
                            @if (isset($item))
                            <input wire:model.defer="buyRequest.amount" type="number" step="0.0001" min="0" class="form-control @error('amount') is-invalid @enderror"
                            placeholder="Sipariş miktarı giriniz (ürünün kendi biriminden değerini giriniz...) {{ ($item) ? '['.$item->itemToUnit->content.']' : '' }}">
                            @endif
                            @error('amount')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Giriş yapılan depo</label>
                            <select data-plugin-selectTwo class="form-control populate @error('increased_warehouse_id') is-invalid @enderror" wire:model.defer="buyRequest.increased_warehouse_id">
                                <option value="0">Lütfen seçiniz...</option>
                                @foreach($itemEnterEnabled as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
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
            </div>
        </form>
    </div>

    <script>

        window.addEventListener('{{ self::model }}alertModalShow', event => {
            $('#{{ self::model }}AlertModal').modal('show');
        });
        window.addEventListener('{{ self::model }}alertModalHide', event => {
            $('#{{ self::model }}AlertModal').modal('hide');
        });


        window.addEventListener('{{ self::model }}alertCancelModalShow', event => {
            $('#{{ self::model }}alertCancelModal').modal('show');
        });
        window.addEventListener('{{ self::model }}alertCancelModalHide', event => {
            $('#{{ self::model }}alertCancelModal').modal('hide');
        });


        window.addEventListener('{{ self::model }}demandItemModalShow', event => {
            $('#{{ self::model }}demandItemModal').modal('show');
        });
        window.addEventListener('{{ self::model }}demandItemModalHide', event => {
            $('#{{ self::model }}demandItemModal').modal('hide');
        });


        window.addEventListener('{{ self::model }}movementmodalShow', event => {
            $('#{{ self::model }}movementmodal').modal('show');
        });
        window.addEventListener('{{ self::model }}movementmodalHide', event => {
            $('#{{ self::model }}movementmodal').modal('hide');
        });


        window.addEventListener('{{ self::model }}warehousemodalShow', event => {
            $('#{{ self::model }}warehousemodal').modal('show');
        });
        window.addEventListener('{{ self::model }}warehousemodalHide', event => {
            $('#{{ self::model }}warehousemodal').modal('hide');
        });


        window.addEventListener('{{ self::model }}WTBmodalShow', event => {
            $('#{{ self::model }}WTBmodal').modal('show');
        });
        window.addEventListener('{{ self::model }}WTBmodalHide', event => {
            $('#{{ self::model }}WTBmodal').modal('hide');
        });


        window.addEventListener('{{ self::model }}openbuymodalShow', event => {
            $('#{{ self::model }}openbuymodal').modal('show');
        });
        window.addEventListener('{{ self::model }}openbuymodalHide', event => {
            $('#{{ self::model }}openbuymodal').modal('hide');
        });

    </script>

    @include('parts.alert')
    @include('parts.alertify')

</div>
