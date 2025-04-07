<div>

    <div class="row">
        <div class="col">
            <section class="card">

                <header class="card-header">
                    <div class="card-actions">
                        <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                        <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
                    </div>
                    <h2 class="card-title">Ürünlerim</h2>
                    <p class="card-subtitle"></p>
                    <p class="card-subtitle">Toplam {{ $data->total() }} kayıttan {{ $data->count() }} adet listeleniyor.</p>
                </header>
                
                <div class="card-body">
                    
                    <button type="button" class="btn btn-success btn-xs mb-2" wire:click="exportMyProductList()">
                        {!! trans('site.excel.export') !!}
                    </button>
                    <div class="col-lg-12 col-sm-12 pb-sm-3 pb-md-0 mb-2">
                        <input wire:model="search" type="search" class="form-control" placeholder="aramak istediğiniz ürünün adını ya da diğer bilgilerini buraya yazınız.">
                    </div>

                    <div class="table-responsive" style="min-height: 300px;">
                        <table class="table table-responsive-md table-hover table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th class="center">#</th>
                                    <th>Ürün kodu</th>
                                    <th>Ürün adı</th>
                                    <th>Stok yeri</th>
                                    <th class="center">Stok miktarı <button type="button" wire:click="toggle_gteORgt" class="btn btn-primary btn-xs">{!! ($gteORgt === '>=' ? '<i class="fas fa-greater-than-equal"></i>':'<i class="fas fa-greater-than"></i>') .'0' !!}</button></th>
                                    <th>Birimi</th>
                                    <th>Ürün tipi</th>
                                    <th>Not</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $row)
                                    <tr>
                                        <td class="center">
                                            <div class="btn-group flex-wrap">
												<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-bs-toggle="dropdown">İşlem menüsü <span class="caret"></span></button>
												<div class="dropdown-menu" role="menu">
                                                    @if(($row->warehouses_id == 23) && $row->erp_items_type != 0 && $row->erp_items_warehouses_amount)
													    <button wire:click="startCleanProduct({{ $row->erp_items_id.','.$row->warehouses_id.','.$row->erp_items_warehouses_amount }})" class="dropdown-item text-1">
                                                            <i class="fas fa-box test-warning"></i>
                                                            Sterilizasyon sürecini başlat
                                                        </button>
                                                    @endif

                                                    @if(Auth::user()->can_buy && $row->erp_items_type == 0)
													    <button wire:click="openBuyModal({{ $row->erp_items_id }})" class="dropdown-item text-1">
                                                            <i class="fas fa-truck-loading"></i>
                                                            Satın alım(Yeni Giriş)
                                                        </button>
                                                    @endif
                                                    
                                                    @if (count(Auth::user()->warehouses)>1)
                                                        <button wire:click="transferItemsBetweenMyWarehousesModal({{ $row->erp_items_id }},{{ $row->warehouses_id }})" class="dropdown-item text-1">
                                                            <i class="fas fa-exchange-alt text-success"></i>
                                                            Depolarım arası aktar
                                                        </button>
                                                    @endif

                                                    <button wire:click="openTransferModal({{ $row->erp_items_id }},{{ $row->warehouses_id }})" class="dropdown-item text-1">
                                                        <i class="fas fa-exchange-alt"></i>
                                                        Farklı depoya transfer et
                                                    </button>

                                                    <button wire:click="process({{ $row->erp_items_id }}, 'demand')" class="dropdown-item text-1">
                                                        <i style="color:green" class="fas fa-bullhorn bx-xs"></i>
                                                        Talep Et
                                                    </button>

                                                    @if (count(Auth::user()->myErpItemAlerts->where('item_id',$row->erp_items_id)->where('warned',FALSE))==0)
                                                        <button wire:click="process({{ $row->erp_items_id }}, 'alertMe')" class="dropdown-item text-1">
                                                            <i style="color:orange" class='bx bxs-bell-ring bx-tada bx-xs' ></i>
                                                            Beni uyar !
                                                        </button>
                                                    @elseif (count(Auth::user()->myErpItemAlerts->where('item_id',$row->erp_items_id)->where('warned',FALSE)->where('perma',TRUE)))
                                                        <button wire:click="process({{ $row->erp_items_id }}, 'alertMe')" class="dropdown-item text-1">
                                                            <i class='bx bxs-bell-off' style='color:#b40000'  ></i>
                                                            Alarmı Kapat
                                                        </button>
                                                    @endif

                                                    <button wire:click="process({{ $row->erp_items_id }}, 'movement')" class="dropdown-item text-1">
                                                        <i class="fas fa-people-carry"></i>
                                                        Stok hareketlerini görüntüle
                                                    </button>

                                                    <button wire:click="process({{ $row->erp_items_id }}, 'warehouse')" class="dropdown-item text-1">
                                                        <i class="fas fa-industry"></i>
                                                        Depolardaki dağılımın miktarını görüntüle
                                                    </button>

                                                    @if ($row->type == 0)
                                                        <button  wire:click="process({{ $row->erp_items_id }}, 'wtb')" class="dropdown-item text-1">
                                                            <i class="fas fa-shopping-cart"></i>
                                                            Satın alma talebi
                                                        </button>
                                                    @endif

												</div>
											</div>
                                        </td>
                                        <td>
                                            @if (count(Auth::user()->myErpItemAlerts->where('item_id',$row->erp_items_id)->where('warned',FALSE)))
                                            <a href="#" wire:click="showCancelAlert({{ $row->erp_items_id }})">
                                                <i style="color:orange" class='bx bxs-bell-off bx-xs'></i>
                                            </a>
                                            @endif
                                            {{ $row->erp_items_code }}
                                        </td>
                                        <td>{{ $row->erp_items_name }}</td>
                                        <td>{{ $row->erp_warehouses_name }}</td>
                                        <td class="center">{{ $row->erp_items_warehouses_amount }}</td>
                                        <td>{{ $row->erp_units_content }}</td>
                                        <td>{{ $row->getType() }}</td>
                                        <td wire:click='note({{ $row->erp_items_id.','.$row->warehouses_id }})' class="center"><i class="far fa-comment-alt"></i></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {!! ($data->count() < 3) ? '<br><br><br><br><br>' : '' !!}
                        <hr>
                        {{ $data->links() }}
                    </div>

                </div>

            </section>
        </div>
    </div>
  

    {{-- modals--}}
    {{-- buy modal --}}
    <div class="modal fade" id="{{ self::model.'openbuymodal' }}" tabindex="-1" aria-labelledby="{{ self::model.'openbuymodalLabel' }}" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <form autocomplete="off" wire:submit.prevent="openBuyRequest">
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
                                @foreach(Auth::user()->warehouses as $warehouse)
                                    <option value="{{ $warehouse->warehouse->id }}">{{ $warehouse->warehouse->name }}</option>
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

    {{-- transfer modal --}}
    <div class="modal fade" id="{{ self::model.'openTransferModal' }}" tabindex="-1" aria-labelledby="{{ self::model.'openTransferModalLabel' }}" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <form autocomplete="off" wire:submit.prevent="openTransferRequest">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-exchange-alt"></i>  {{ ($item) ? $item->code.' - '. $item->name : '' }}</h5>
                        <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if($item)
                        <div class="mb-3">
                            <label class="form-label">Transfer edilecek miktar (ürünün kendi biriminden değeri) {{ ($item) ? '['.$item->itemToUnit->content.']' : '' }}</label>
                            <input wire:model.defer="transferRequest.amount" type="number" step="0.0001" min="0" max="{{ ($item->stock($warehouseId)) ? $item->stock($warehouseId)->amount : '0' }}" class="form-control @error('amount') is-invalid @enderror"
                                placeholder="Ürün miktarı giriniz (ürünün kendi biriminden değerini giriniz...) {{ ($item) ? '['.$item->itemToUnit->content.']' : '' }}">
                            @error('amount')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label">Transfer edilecek depo</label>
                            <select data-plugin-selectTwo class="form-control populate @error('increased_warehouse_id') is-invalid @enderror" wire:model.defer="transferRequest.increased_warehouse_id">
                                <option value="0">Lütfen seçiniz...</option>
                                @foreach($warehouses as $warehouse)
                                    @if($warehouseId != $warehouse->id)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                    @endif
                                @endforeach
                                @if (Auth::user()->can_exit)
                                    <option value="out">Sistem Dışına Transfer (Stoğu azaltır)</option>
                                @endif
                            </select>
                            @error('increased_warehouse_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <div class="form-group">
                                <label class="control-label" for="textareaAutosize">İşlem açıklaması</label>
                                <textarea wire:model.defer="transferRequest.content" class="form-control" rows="3" id="textareaAutosize" data-plugin-textarea-autosize></textarea>
                            </div>
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


    {{-- process modal --}}
    <div class="modal fade" id="{{ self::model.'openProcessModal' }}" tabindex="-1" aria-labelledby="{{ self::model.'openProcessModalLabel' }}" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <form autocomplete="off" wire:submit.prevent="openProcessRequest">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-cogs"></i>  {{ ($item) ? $item->code.' - '. $item->name : '' }}</h5>
                        <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        @if($item)
                        <div class="mb-3">
                            <label class="form-label">İşleme sokulacak ürün miktarını giriniz. (ürünün kendi biriminden değeri) {{ ($item) ? '['.$item->itemToUnit->content.']' : '' }}</label>
                            <input wire:model.defer="processRequest.amount" type="number" step="0.0001" min="0" max="{{ ($item->stock($warehouseId)) ? $item->stock($warehouseId)->amount : '0' }}" class="form-control @error('amount') is-invalid @enderror"
                                placeholder="Ürün miktarı giriniz (ürünün kendi biriminden değerini giriniz...) {{ ($item) ? '['.$item->itemToUnit->content.']' : '' }}">
                            @error('amount')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">İşleme sokulan ürün fire miktarını giriniz. (ürünün kendi biriminden değeri) {{ ($item) ? '['.$item->itemToUnit->content.']' : '' }}</label>
                            <input wire:model.defer="processRequest.wastage" type="number" step="0.0001" min="0" max="{{ ($item->stock($warehouseId)) ? $item->stock($warehouseId)->amount : '0' }}" class="form-control @error('wastage') is-invalid @enderror"
                                placeholder="Fire miktarı giriniz (ürünün kendi biriminden değerini giriniz...) {{ ($item) ? '['.$item->itemToUnit->content.']' : '' }}">
                            @error('wastage')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        @endif

                        <div class="mb-3">
                            <div class="form-group">
                                <label class="control-label" for="textareaAutosize">İşlem açıklaması</label>
                                <textarea wire:model.defer="processRequest.content" class="form-control @error('content') is-invalid @enderror" rows="3" id="textareaAutosize" data-plugin-textarea-autosize></textarea>
                            </div>
                            @error('content')
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

    {{-- alert modal --}}
    @if (isset($setAlertData))
        <div class="modal fade" id="{{ self::model }}AlertModal" tabindex="-1" aria-labelledby="{{ self::model }}AlertModalLabel" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog">
                <div class="modal-dialog">
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

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('site.modal.button.cancel') }}</button>
                                <button type="submit" class="btn btn-primary">{{ trans('site.modal.button.confirm') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- modal demand --}}
    @if (isset($setDemandData))
        <div class="modal fade" id="{{ self::model }}demandItemModal" tabindex="-1" aria-labelledby="{{ self::model }}demandItemModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-dialog">
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
                                <input wire:model.defer="transferRequest.amount" type="number" step="0.0001" min="0" class="form-control @error('amount') is-invalid @enderror"
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
                                        <option value="{{ $warehouse->warehouse_id }}">{{ $warehouse->warehouse->name.' - ['.$warehouse->amount.' '.$warehouse->item->itemToUnit->code.']' }}</option>
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
                </form>
            </div>
        </div>
    @endif

    {{-- modal movements --}}
    <div class="modal fade" id="{{ self::model.'movementmodal' }}" tabindex="-1" aria-labelledby="{{ self::model.'movementmodalLabel' }}" aria-hidden="true">
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
                                                <td class="center">{{ $move->getApproval?->name }}</td>
                                            @endif
                                            <td class="center">{{ $move->created_at->format('Y-m-d H:i') }}</td>                                                
                                            <td class="center">{{ (in_array($move->type, [1,2,3,4,10,11,12,13])) ? $move->old_warehouse_amount.' '.$move->item->itemToUnit->code : '' }}</td>
                                            <td class="center">{{ (in_array($move->type, [1,2,3,4,10,11,12,13])) ? $move->old_total_amount.' '.$move->item->itemToUnit->code : '' }}</td>

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
                        <h5 class="card-title">{{ ($itemDistribution) ? $itemDistribution->code.' - '.$itemDistribution->name : '' }} - Ürün depo değılımı</h5>
                        <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">

                        @if($itemDistribution)
                            @if($itemDistribution->stocks->count() > 0)
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
                                        @foreach ($itemDistribution->stocks as $stock)
                                            <tr>
                                                <td class="center">{{ $stock->warehouse->code }}</td>
                                                <td>{{ $stock->warehouse->name }}</td>
                                                <td class="center">{{ $stock->amount }} {{ $stock->item->itemToUnit->code }}</td>
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

    {{-- modal note --}}
    @if (isset($noteItemId))
        <div class="modal" role="dialog" id="{{ self::model }}noteModal" tabindex="-1" aria-labelledby="{{ self::model }}noteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form autocomplete="off" wire:submit.prevent="addNote()">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Notum - {{ $noteAddedItem->name }}</h5>
                            <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label class="control-label" for="textareaAutosize">Ürün notu</label>
                                        <textarea wire:model.defer="noteText" class="form-control" rows="3" id="textareaAutosize" data-plugin-textarea-autosize></textarea>
                                    </div>
                                </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">{{ trans('site.modal.button.save') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
    @endif

    {{-- modal wtb --}}
    @if ($action == 'wtb')
        <div class="modal fade" id="{{ self::model }}WTBmodal" tabindex="-1" aria-labelledby="{{ self::model }}WTBmodalLabel" aria-hidden="true">
            <form autocomplete="off" wire:submit.prevent="wtbRequest()">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Hammadde Satın alma talebi</h5>
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

    {{-- modal Finish Production --}}
    @if (count($cleanProductModalData))
        <div class="modal fade" id="{{ self::model }}sendToCleanProductModal" tabindex="-1" aria-labelledby="{{ self::model }}sendToCleanProductModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-dialog">
                <form autocomplete="off" wire:submit.prevent="sendProductToCleanRoom()">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">ÜRÜNÜ STERİLİZASYONA GÖNDER</h5>
                            <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><u>
                                {{ $item->code }} - {{ $item->name }}
                            </u></p>
                            
                            <div class="mb-3">
                                <label class="form-label" for="cleanProductAmount">Sterilizasyon sürecine başlayacak ürün miktarı [{{ $item->itemToUnit->content }}]</label>
                                <input wire:model.defer="cleanProductModalData.amount" type="number" step="0.0001" min="0" max="{{ $cleanProductMaxAmount }}" placeholder="{{ $item->itemToUnit->content }}" class="form-control @error('amount') is-invalid @enderror">
                                @error('amount')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                {{-- @dd($data->whereIn('erp_items_type',[0,1])->where('warehouses_id',23)->where('erp_items_warehouses_amount',">",0)->where("erp_items_id","!=",$itemId)) --}}
                                <label class="form-label">Sterilizasyonda kullanılan malzemeyi seçiniz</label>
                                <select data-plugin-selectTwo class="form-control populate mb-1 @error('use_item_1.item_id') is-invalid @enderror" wire:model.defer="cleanProductModalData.use_item_1.item_id">
                                    <option value="">Lütfen seçiniz...</option>
                                    @foreach($data->whereIn('erp_items_type',[0,1])->where('warehouses_id',23)->where('erp_items_warehouses_amount',">",0)->where("erp_items_id","!=",$itemId) as $row)
                                        <option value="{{ $row->erp_items_id }}">{{ $row->erp_items_name ." [".$row->erp_items_warehouses_amount."]" }}</option>
                                    @endforeach
                                    <input wire:model.defer="cleanProductModalData.use_item_1.amount" type="number" min="0" class="form-control @error('use_item_1.amount') is-invalid @enderror">
                                </select>
                                @error('use_item_1.amount')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <hr>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Sterilizasyonda kullanılan malzemeyi seçiniz</label>
                                <select data-plugin-selectTwo class="form-control populate mb-1 @error('use_item_2.item_id') is-invalid @enderror" wire:model.defer="cleanProductModalData.use_item_2.item_id">
                                    <option value="">Lütfen seçiniz...</option>
                                    @foreach($data->whereIn('erp_items_type',[0,1])->where('warehouses_id',23)->where('erp_items_warehouses_amount',">",0)->where("erp_items_id","!=",$itemId) as $row)
                                        <option value="{{ $row->erp_items_id }}">{{ $row->erp_items_name ." [".$row->erp_items_warehouses_amount."]" }}</option>
                                    @endforeach
                                    <input wire:model.defer="cleanProductModalData.use_item_2.amount" type="number" min="0" class="form-control @error('use_item_2.amount') is-invalid @enderror">
                                </select>
                                @error('use_item_2.amount')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <hr>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Sterilizasyonda kullanılan malzemeyi seçiniz</label>
                                <select data-plugin-selectTwo class="form-control populate mb-1 @error('use_item_3.item_id') is-invalid @enderror" wire:model.defer="cleanProductModalData.use_item_3.item_id">
                                    <option value="">Lütfen seçiniz...</option>
                                    @foreach($data->whereIn('erp_items_type',[0,1])->where('warehouses_id',23)->where('erp_items_warehouses_amount',">",0)->where("erp_items_id","!=",$itemId) as $row)
                                        <option value="{{ $row->erp_items_id }}">{{ $row->erp_items_name ." [".$row->erp_items_warehouses_amount."]" }}</option>
                                    @endforeach
                                    <input wire:model.defer="cleanProductModalData.use_item_3.amount" type="number" min="0" class="form-control @error('use_item_3.amount') is-invalid @enderror">
                                </select>
                                @error('use_item_3.amount')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <hr>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Sterilizasyonda kullanılan malzemeyi seçiniz</label>
                                <select data-plugin-selectTwo class="form-control populate mb-1 @error('use_item_4.item_id') is-invalid @enderror" wire:model.defer="cleanProductModalData.use_item_4.item_id">
                                    <option value="">Lütfen seçiniz...</option>
                                    @foreach($data->whereIn('erp_items_type',[0,1])->where('warehouses_id',23)->where('erp_items_warehouses_amount',">",0)->where("erp_items_id","!=",$itemId) as $row)
                                        <option value="{{ $row->erp_items_id }}">{{ $row->erp_items_name ." [".$row->erp_items_warehouses_amount."]" }}</option>
                                    @endforeach
                                    <input wire:model.defer="cleanProductModalData.use_item_4.amount" type="number" min="0" class="form-control @error('use_item_4.amount') is-invalid @enderror">
                                </select>
                                @error('use_item_4.amount')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('site.modal.button.cancel') }}</button>
                            <button type="submit" class="btn btn-primary">{{ trans('site.modal.button.confirm') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
    {{-- modal transferItemsBetweenMyWarehousesModal --}}
    @if (!is_null($transferItemsBetweenMyWarehousesData))
        <div class="modal fade" id="{{ self::model }}transferItemsBetweenMyWarehousesModal" tabindex="-1" aria-labelledby="{{ self::model }}transferItemsBetweenMyWarehousesModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-dialog">
                <form autocomplete="off" wire:submit.prevent="transferItemsBetweenMyWarehouses()">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Diğer Depoma Aktar</h5>
                            <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><u>
                                {{ $item->code }} - {{ $item->name }}
                            </u></p>
                            <p>
                                {{ $warehouses->find($warehouseId)?->name }} Stoğu : <u>{{ $this->transferItemsBetweenMyWarehousesData->amount }}</u>
                            </p>
                            <div class="mb-3">
                                <label class="form-label">Transfer edilecek miktar</label>
                                <div class="input-group mb-3">
                                    <input wire:model.defer="transferItemsBetweenMyWarehousesModalData.amount" type="number" min="0" step="0.0001" class="form-control @error('amount') is-invalid @enderror"
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
                                <label class="form-label">Transfer edilecek depo</label>
                                <select class="form-control populate @error('increased_warehouse_id') is-invalid @enderror" wire:model.defer="transferItemsBetweenMyWarehousesModalData.increased_warehouse_id">
                                    <option value="0" selected>Lütfen seçiniz...</option>
                                    @foreach(Auth::user()->warehouses as $row)
                                        @if ($warehouseId != $row->warehouse->id)
                                            <option value="{{ $row->warehouse->id }}">{{ $row->warehouse->name }}</option>
                                        @endif
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
                            <button type="submit" class="btn btn-primary">{{ trans('site.modal.button.confirm') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

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

        window.addEventListener('{{ self::model }}openbuymodalShow', event => {
            $('#{{ self::model }}openbuymodal').modal('show');
        });
        window.addEventListener('{{ self::model }}openbuymodalHide', event => {
            $('#{{ self::model }}openbuymodal').modal('hide');
        });

        window.addEventListener('{{ self::model }}openTransferModalShow', event => {
            $('#{{ self::model }}openTransferModal').modal('show');
        });
        window.addEventListener('{{ self::model }}openTransferModalHide', event => {
            $('#{{ self::model }}openTransferModal').modal('hide');
        });

        window.addEventListener('{{ self::model }}openProcessModalShow', event => {
            $('#{{ self::model }}openProcessModal').modal('show');
        });
        window.addEventListener('{{ self::model }}openProcessModalHide', event => {
            $('#{{ self::model }}openProcessModal').modal('hide');
        });

        window.addEventListener('{{ self::model }}noteModalShow', event => {
            $('#{{ self::model }}noteModal').modal('show');
        });
        window.addEventListener('{{ self::model }}noteModalHide', event => {
            $('#{{ self::model }}noteModal').modal('hide');
        });

        window.addEventListener('{{ self::model }}WTBmodalShow', event => {
            $('#{{ self::model }}WTBmodal').modal('show');
        });
        window.addEventListener('{{ self::model }}WTBmodalHide', event => {
            $('#{{ self::model }}WTBmodal').modal('hide');
        });

        window.addEventListener('{{ self::model }}sendToCleanProductModalShow', event => {
            $('#{{ self::model }}sendToCleanProductModal').modal('show');
        });
        window.addEventListener('{{ self::model }}sendToCleanProductModalHide', event => {
            $('#{{ self::model }}sendToCleanProductModal').modal('hide');
        });

        window.addEventListener('{{ self::model }}transferItemsBetweenMyWarehousesModalShow', event => {
            $('#{{ self::model }}transferItemsBetweenMyWarehousesModal').modal('show');
        });
        window.addEventListener('{{ self::model }}transferItemsBetweenMyWarehousesModalHide', event => {
            $('#{{ self::model }}transferItemsBetweenMyWarehousesModal').modal('hide');
        });

        window.addEventListener('{{ self::model }}itemOutShow', event => {
            $('#{{ self::model }}itemOut').modal('show');
        });
        window.addEventListener('{{ self::model }}itemOutHide', event => {
            $('#{{ self::model }}itemOut').modal('hide');
        });

    </script>

    {{-- middleware'den redirect yiyince - parts.alert - çalışmıyor --}}
    {{-- @include('parts.alert') --}}
    {{-- middleware'den redirect yiyince - parts.alert - çalışmıyor --}}
    @include('parts.alertify')


</div>
