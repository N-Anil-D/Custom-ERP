<div>
    <div class="row">
        <div class="col">
            @foreach ($warehouses as $warehouse)
                <button type="button" wire:click="wareHouseSelect({{ $warehouse->id }})" class="btn {{ $warehouseButtonChange == $warehouse->id ? '':'btn-outline' }} btn-info m-2">{{ $warehouse?->name }}</button>
            @endforeach
        </div>
    </div>

    @if (isset($currentWarehouseId))
        <div class="row mt-3">
            <div class="col">
                <section class="card">
                    <header class="card-header">
                        <h2 class="card-title">{{ $warehouseData?->name }}</h2>
                        <h3 class="card-subtitle">{{ $warehouseData?->code .' - '. $warehouseData?->content }}</h3>
                        <p class="card-subtitle">Toplam {{ $warehouseItems->total() }} kayıttan {{ $warehouseItems->count() }} adet listeleniyor.</p>
                    </header>
                    <div class="card-body">
                        <div class="col-lg-12 col-sm-12 pb-sm-3 pb-md-0 mb-2">
                            <input wire:model="search" type="search" class="form-control" placeholder="Aramak istediğiniz ürünün adını ya da diğer bilgilerini buraya yazınız.">
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#ID</th>
                                        <th>Ürün Kodu</th>
                                        <th>Türü</th>
                                        <th>Ürün Adı</th>
                                        <th>Miktar (Birim)</th>
                                        {{-- <th>En son değiştirme tarihi</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($warehouseItems as $warehouseItem)
                                        @if (!is_null($warehouseItem))
                                            <tr>
                                                <td>
                                                    <div class="btn-group flex-wrap">
                                                        <a style="text-decoration: none;" href="#" class="dropdown-toggle" data-bs-toggle="dropdown">{{ $warehouseItem->id }}<span class="caret"></span></a>
                                                        <div class="dropdown-menu" role="menu">
                                                            <button wire:click="process({{ $warehouseItem->id }}, 'demand')" class="dropdown-item text-1">
                                                                <i style="color:green" class="fas fa-bullhorn bx-xs"></i>
                                                                Talep Et
                                                            </button>
                                                            <button wire:click="process({{ $warehouseItem->id }}, 'movement')" class="dropdown-item text-1">
                                                                <i class="fas fa-people-carry"></i>
                                                                Stok hareketlerini görüntüle
                                                            </button>
                        
                                                            <button wire:click="process({{ $warehouseItem->id }}, 'warehouse')" class="dropdown-item text-1">
                                                                <i class="fas fa-industry"></i>
                                                                Depolardaki dağılımın miktarını görüntüle
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $warehouseItem->code }}</td>
                                                <td>{{ $warehouseItem->getType() }}</td>
                                                <td>{{ $warehouseItem?->name }}</td>
                                                <td>{{ $warehouseItem?->stock($currentWarehouseId)->amount .' ('. $warehouseItem->itemToUnit->code.')' }}</td>
                                                {{-- <td>{{ $warehouseItem->updated_at }}</td> --}}
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $warehouseItems->links() }}
                    </div>

                </section>
            </div>
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
                        <h5 class="card-title">{{ ($item) ? $item->code.' - '.$item?->name.' - Toplam stok : '.$item->stocks->sum('amount'). $item->itemToUnit->code : '' }}</h5>
                        <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">

                        @if($movements)
                            @if($movements->count() > 0)
                            <div class="table-responsive" style="min-height: 300px;">
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
                                                    <td class="center" colspan="2">{{ $move->getSender?->name }}</td>
                                                @else
                                                    <td class="center">{{ $move->getSender?->name }}</td>
                                                    <td class="center">{{ $move->getApproval?->name }}</td>
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
        </form>
    </div>

        {{-- modal warehouse --}}
    <div class="modal fade" id="{{ self::model.'warehousemodal' }}" tabindex="-1" aria-labelledby="{{ self::model.'warehousemodalLabel' }}"
        aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="mx-2 btn-group flex-wrap">
                            <div class="btn-group flex-wrap">
                                <button type="button" class="btn btn-success btn-xs" wire:click="exportStockDispersion()">
                                    {!! trans('site.excel.export') !!}
                                </button>
                            </div>
                        </div>
                        <h5 class="card-title">{{ ($item) ? $item->code.' - '.$item?->name : '' }} - Ürün depo dağılımı</h5>
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
        </form>
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
                                {{ $setDemandData->code }} - {{ $setDemandData?->name }} <span style="font-weight:300;">({{ $setDemandData->getType() }})</span><br>
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
                                        <option value="{{ $warehouse->warehouse_id }}">{{ $warehouse->warehouse?->name.' - ['.$warehouse->amount.' '.$warehouse->item->itemToUnit->code.']' }}</option>
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
                                        <option value="{{ $warehouse->warehouse_id }}">{{ $warehouse->warehouse?->name }}</option>
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


    <script>

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

        window.addEventListener('{{ self::model }}demandItemModalShow', event => {
            $('#{{ self::model }}demandItemModal').modal('show');
        });
        window.addEventListener('{{ self::model }}demandItemModalHide', event => {
            $('#{{ self::model }}demandItemModal').modal('hide');
        });

    </script>

    @include('parts.alert')
    @include('parts.alertify')
</div>

