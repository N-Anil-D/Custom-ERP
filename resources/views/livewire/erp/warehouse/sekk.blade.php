<div>
    <div class="row">
        <div class="col">
            <section class="card form-wizard" id="w2">
                <div class="tabs">
                    <ul class="nav nav-tabs nav-justify wizard-steps wizard-steps-style-2">
                        <li class="nav-item {{ ($tab=='se'||$tab==null) ? 'active':'' }}">
                            <a href="#se" wire:click="tagProcess('se')" data-bs-toggle="tab" class="nav-link text-center">
                                <span class="badge badge-primary"><i class="fas fa-hand-sparkles"></i></span>
                                Steril + Etiket
                            </a>
                        </li>
                        <li class="nav-item {{ $tab=='qualityControl' ? 'active':'' }}">
                            <a href="#qualityControl" wire:click="tagProcess('qualityControl')" data-bs-toggle="tab" class="nav-link text-center">
                                <span class="badge badge-warning"><i class="fas fa-vial"></i></span>
                                Kalite
                            </a>
                        </li>
                        <li class="nav-item {{ $tab=='package' ? 'active':'' }}">
                            <a href="#package" wire:click="tagProcess('package')" data-bs-toggle="tab" class="nav-link text-center">
                                <span class="badge badge-success"><i class="fas fa-box-open"></i></span>
                                Koli
                            </a>
                        </li>
                    </ul>
{{-- ################################################################# se ################################################################################### --}}
                    <div class="tab-content p-0 pt-3">
                        <div id="se" class="tab-pane p-3 {{ ($tab=='se'||$tab==null) ? 'active':'' }}">
                            <section class="card">
                                <header class="card-header" style="{{ Auth()->user()->theme == 1 ? 'background:#21262d;':'background:#adadad;' }}">
                                    <p class="card-subtitle">Toplam {{ $seDatas->total() }} kayıttan {{ $seDatas->count() }} adet listeleniyor.</p>
                                </header>
                                
                                <div class="card-body" style="{{ Auth()->user()->theme == 0 ? 'background:#d3d3d3;':'' }}">

                
                                    <div class="table-responsive" style="min-height: 300px;">
                                        <table class="table table-responsive-md table-hover table-bordered mb-0">
                                            <thead>
                                                <tr>
                                                    @if(Auth::user()->quality_control) <th class="center">#</th> @endif
                                                    <th>Ürün adı</th>
                                                    <th class="center">Sterilizasyon ve karantina süreci tamamlandı mı ?</th>
                                                    <th>Miktar</th>
                                                    <th>Durum</th>
                                                    <th>Tarih</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($seDatas as $row)
                                                    <tr>
                                                        @if(Auth::user()->quality_control)
                                                        <td class="center">
                                                            <div class="btn-group flex-wrap">
                                                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-bs-toggle="dropdown">İşlemler <span class="caret"></span></button>
                                                                <div class="dropdown-menu" role="menu">
                                                                    @if(Auth::user()->quality_control)
                                                                        <button wire:click="startQualityControlModal({{ $row->id }})" class="dropdown-item text-1">
                                                                            <i class="fas fa-vial"></i>
                                                                            Kalite kontrolü başlat
                                                                        </button>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                        @endif
                                                        <td>{{ '( '.$row->id .' ) '. $row->item->name }}</td>
                                                        <td class="center">
                                                            <input wire:click="seProcess({{ $row->id }}, 'clean_status')" type="checkbox" {{ ($row->clean_status) ? 'checked="checked"' : '' }}>                                            
                                                        </td>
                                                        <td>{{ $row->amount .' '. $row->item->itemToUnit->content }}</td>
                                                        <td>{{ $row->getGeneralStatus() }}</td>
                                                        <td>{{ $row->updated_at }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        {!! ($seDatas->count() < 4) ? '<br><br><br>' : '' !!}
                                        <hr>
                                        {{ $seDatas->links() }}
                                    </div>
                
                                </div>
                            </section>
                        </div>
{{-- ################################################################# qualityControl ################################################################################### --}}
                        <div id="qualityControl" class="tab-pane p-3 {{ $tab=='qualityControl' ? 'active':'' }}">
                            <section class="card">
                                <header class="card-header" style="{{ Auth()->user()->theme == 1 ? 'background:#21262d;':'background:#adadad;' }}">
                                    <p class="card-subtitle">Toplam {{ $qualityControlDatas->total() }} kayıttan {{ $qualityControlDatas->count() }} adet listeleniyor.</p>
                                </header>
                                
                                <div class="card-body" style="{{ Auth()->user()->theme == 0 ? 'background:#d3d3d3;':'' }}">
                                    <div class="table-responsive" style="min-height: 300px;">
                                        <table class="table table-responsive-md table-hover table-bordered mb-0">
                                            <thead>
                                                <tr>
                                                    @if(Auth::user()->quality_control) <th class="center">#</th> @endif
                                                    <th>İŞ EMRİ NO</th>
                                                    <th>Ürün adı</th>
                                                    <th class="center">Sterilizasyon ve karantina süreci tamamlandı mı ?</th>
                                                    <th>Miktar</th>
                                                    <th>Durum</th>
                                                    <th>Tarih</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($qualityControlDatas as $row)
                                                    <tr>
                                                        @if(Auth::user()->quality_control)
                                                        <td class="center">
                                                            <div class="btn-group flex-wrap">
                                                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-bs-toggle="dropdown">İşlemler <span class="caret"></span></button>
                                                                <div class="dropdown-menu" role="menu">
                                                                    @if(Auth::user()->quality_control)
                                                                        <button wire:click="endQualityControlModal({{ $row->id }},1)" class="dropdown-item text-1">
                                                                            <i class="fas fa-vial"></i>
                                                                            Kalite kontrolü bitir
                                                                        </button>
                                                                        <button wire:click="endQualityControlModal({{ $row->id }},2)" class="dropdown-item text-1">
                                                                            <i class="fas fa-hand-paper"></i>
                                                                            İmha Et
                                                                        </button>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                        @endif
                                                        <td>{{ $row->work_order_no }}</td>
                                                        <td>{{ '( '.$row->id .' ) '. $row->item->name }}</td>
                                                        <td class="center">
                                                            <input wire:click="seProcess({{ $row->id }}, 'clean_status')" type="checkbox" {{ ($row->clean_status) ? 'checked="checked"' : '' }}>
                                                        </td>
                                                        <td>{{ $row->amount .' '. $row->item->itemToUnit->content }}</td>
                                                        <td>{{ $row->getGeneralStatus() }}</td>
                                                        <td>{{ $row->updated_at }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        {!! ($seDatas->count() < 4) ? '<br><br><br>' : '' !!}
                                        <hr>
                                        {{ $seDatas->links() }}
                                    </div>
                
                                </div>
                            </section>
                        </div>
{{-- ################################################################# package ################################################################################### --}}
                        <div id="package" class="tab-pane p-3 {{ $tab=='package' ? 'active':'' }}">
                            <section class="card">
                                <header class="card-header" style="{{ Auth()->user()->theme == 1 ? 'background:#21262d;':'background:#adadad;' }}">
                                    <p class="card-subtitle">Toplam {{ $readyToPackageDatas->total() }} kayıttan {{ $readyToPackageDatas->count() }} adet listeleniyor.</p>
                                </header>
                                
                                <div class="card-body" style="{{ Auth()->user()->theme == 0 ? 'background:#d3d3d3;':'' }}">

                                    <div class="table-responsive" style="min-height: 300px;">
                                        <table class="table table-responsive-md table-hover table-bordered mb-0">
                                            <thead>
                                                <tr>
                                                    @if(in_array(23,Auth::user()->warehouses->pluck('warehouse_id')->toArray())) <th class="center">#</th> @endif
                                                    <th>LOT NO</th>
                                                    <th>Ürün adı</th>
                                                    <th class="center">Onaylayan</th>
                                                    <th class="center">Kolilendi mi?</th>
                                                    <th>Miktar</th>
                                                    <th>Durum</th>
                                                    <th>Tarih</th>
                                                    <th>Not</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($readyToPackageDatas as $row)
                                                    <tr>
                                                        @if(in_array(23,Auth::user()->warehouses->pluck('warehouse_id')->toArray()))
                                                        <td class="center">
                                                            <div class="btn-group flex-wrap">
                                                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-bs-toggle="dropdown">İşlemler <span class="caret"></span></button>
                                                                <div class="dropdown-menu" role="menu">
                                                                    <button wire:click="finishProductModal({{ $row->id }})" class="dropdown-item text-1">
                                                                        <i class="fas fa-archive"></i>
                                                                        Bitmiş ürünlere aktar
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        @endif
                                                        <td>{{ $row->lot_no }}</td>
                                                        <td>{{ '( '.$row->id .' ) '. $row->item->name }}</td>
                                                        <td class="center">
                                                            {{ $row->user->name }}
                                                        </td>
                                                        <td class="center">
                                                            <input wire:click="kProcess({{ $row->id }})" type="checkbox" {{ ($row->general_status == 5) ? 'checked="checked"' : '' }}>
                                                        </td>
                                                        <td>{{ $row->amount .' '. $row->item->itemToUnit->content }}</td>
                                                        <td>{{ $row->getGeneralStatus() }}</td>
                                                        <td>{{ $row->updated_at }}</td>
                                                        <td class="center"><a class="{{ $row->text == null || $row->text=="" ? 'hidden':'' }}" data-bs-toggle="tooltip" data-bs-placement="left" title="{{ $row->text }}" href="#"><i class="far fa-comment-alt"></i></a></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        {!! ($seDatas->count() < 4) ? '<br><br><br>' : '' !!}
                                        <hr>
                                        {{ $seDatas->links() }}
                                    </div>
                
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    {{-- modals --}}

    @if ($selectedItem)
        <div class="modal fade" id="{{ self::model.'startQualityControlModal' }}" tabindex="-1" aria-labelledby="{{ self::model.'startQualityControlModallabel' }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form autocomplete="off" wire:submit.prevent="startQualityControl">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                Kalite kontrol süreci başlatılsın mı ?
                            </h5>
                            <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            {{ $selectedItem->item->name }}
                            <br>
                            {{ $selectedItem->amount .' '. $selectedItem->item->itemToUnit->content }}
                            <div class="my-3">
                                <label class="form-label" for="work_order_no"><u><b>İŞ EMRİ NO</b></u></label>
                                <input wire:model.defer="startQualityControlModalData.work_order_no" type="text" id="work_order_no" class="form-control @error('work_order_no') is-invalid @enderror" >
                                @error('work_order_no')
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
                    </form>
                </div>
            </div>
        </div>
    @endif
    
    @if ($selectedItem)
        <div class="modal fade" id="{{ self::model.'endQualityControlModal' }}" tabindex="-1" aria-labelledby="{{ self::model.'endQualityControlModallabel' }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form autocomplete="off" wire:submit.prevent="endQualityControl()">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                Kalite kontrol süreci bitirilsin mi ?
                            </h5>
                            <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            {{ $selectedItem->item->name }}
                            <br>
                            {{ $selectedItem->amount .' '. $selectedItem->item->itemToUnit->content }}
                            @if ($endQualityControlproccess == 1)
                                {{-- <div class="my-3">
                                    <label class="form-label" for="lotNo"><u><b>LOT NO</b></u></label>
                                    <input wire:model.defer="endQualityControlModalData.lotNo" type="text" id="lotNo" class="form-control @error('lotNo') is-invalid @enderror"
                                        placeholder="LOT NO">
                                    @error('lotNo')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div> --}}
                                <div class="my-3">
                                    <label class="form-label" for="wastedItemCount">Kalite kontrolde <u><b> HARCANAN</b></u> ürün miktarı [{{ $selectedItem->item->itemToUnit->content }}]</label>
                                    <input wire:model.defer="endQualityControlModalData.wastedItemCount" type="number" step="0.0001" min="0" max="{{ $maxProductAmount }}" id="wastedItemCount" class="form-control @error('wastedItemCount') is-invalid @enderror"
                                        placeholder="[{{ $selectedItem->item->itemToUnit->content }}]">
                                    @error('wastedItemCount')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            {{-- @elseif ($endQualityControlproccess == 2) --}}
                            @endif
                            <div class="my-3">
                                <label class="form-label" for="text"><u><b>Kalite Kontrol Müdürüne Bildirilecek Açıklama :</b></u></label>
                                <textarea wire:model.defer="endQualityControlModalData.text"  id="text" class="form-control" rows="3" @error('text') is-invalid @enderror" data-plugin-textarea-autosize></textarea>
                                @error('text')
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
                    </form>
                </div>
            </div>
        </div>
    @endif
    
    @if ($selectedItem)
        <div class="modal fade" id="{{ self::model.'finishProductModal' }}" tabindex="-1" aria-labelledby="{{ self::model.'finishProductModallabel' }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form autocomplete="off" wire:submit.prevent="finishProduct()">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                Bitmiş ürümlere eklensin mi ?
                            </h5>
                            <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <b>LOT NO : </b>{{ $selectedItem->lot_no }}
                            <br>
                            <b>ÜRÜN ADI : </b>{{ $selectedItem->item->name }}
                            <br>
                            {{-- <b>MİKTAR : </b>{{ $selectedItem->amount .' '. $selectedItem->item->itemToUnit->content }} --}}
                            <div class="my-3">
                                <label class="form-label" for="amount">MİKTAR</label>
                                <input wire:model.defer="finishProductModalData.amount" type="number" step="1" min="0" max="{{ $selectedItem->amount }}" id="amount" class="form-control @error('amount') is-invalid @enderror"
                                    placeholder="[{{ $selectedItem->item->itemToUnit->content }}]">
                                @error('amount')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            {{-- <div class="my-3">
                                <label class="form-label" for="lot">LOT</label>
                                <input wire:model.defer="finishProductModalData.lot_no" type="text" id="lot" class="form-control @error('lot_no') is-invalid @enderror"
                                    placeholder="Ürün LOT">
                                @error('lot_no')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div> --}}
                            {{-- <div class="my-3">
                                <label class="form-label" for="send_to">Gönderilecek kurum veya kişi</label>
                                <input wire:model.defer="finishProductModalData.send_to" type="text" id="send_to" class="form-control @error('send_to') is-invalid @enderror"
                                    placeholder="Gönderilecek kurum veya kişi">
                                @error('send_to')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div> --}}

                            <div class="mb-3">
                                <label class="form-label" for="warehouse_id">Bitmiş ürünün bulundurulacağı depo</label>
                                <select data-plugin-selectTwo class="form-control populate @error('warehouse_id') is-invalid @enderror" id="warehouse_id" wire:model.defer="finishProductModalData.warehouse_id" required>
                                    <option value="" selected>Depo Seçiniz</option>
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                                @error('warehouse_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <div class="form-group row pb-3">
                                    <label class="form-label pt-2" for="send_date">Paketlenme Tarihi</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </span>
                                        <input type="date" wire:model.defer="finishProductModalData.send_date" id="send_date" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Sterilizasyonda kullanılan malzemeyi seçiniz</label>
                                <select data-plugin-selectTwo class="form-control populate mb-1 @error('use_item_1.item_id') is-invalid @enderror" wire:model.defer="finishProductModalData.use_item_1.item_id">
                                    <option value="">Lütfen seçiniz...</option>
                                    @foreach($data->whereIn('erp_items_type',[0,1])->where('warehouses_id',23)->where('erp_items_warehouses_amount',">",0) as $row)
                                        <option value="{{ $row->erp_items_id }}">{{ $row->erp_items_name ." [".$row->erp_items_warehouses_amount."]" }}</option>
                                    @endforeach
                                    <input wire:model.defer="finishProductModalData.use_item_1.amount" type="number" min="0" class="form-control @error('use_item_1.amount') is-invalid @enderror">
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
                                <select data-plugin-selectTwo class="form-control populate mb-1 @error('use_item_2.item_id') is-invalid @enderror" wire:model.defer="finishProductModalData.use_item_2.item_id">
                                    <option value="">Lütfen seçiniz...</option>
                                    @foreach($data->whereIn('erp_items_type',[0,1])->where('warehouses_id',23)->where('erp_items_warehouses_amount',">",0) as $row)
                                        <option value="{{ $row->erp_items_id }}">{{ $row->erp_items_name ." [".$row->erp_items_warehouses_amount."]" }}</option>
                                    @endforeach
                                    <input wire:model.defer="finishProductModalData.use_item_2.amount" type="number" min="0" class="form-control @error('use_item_2.amount') is-invalid @enderror">
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
                                <select data-plugin-selectTwo class="form-control populate mb-1 @error('use_item_3.item_id') is-invalid @enderror" wire:model.defer="finishProductModalData.use_item_3.item_id">
                                    <option value="">Lütfen seçiniz...</option>
                                    @foreach($data->whereIn('erp_items_type',[0,1])->where('warehouses_id',23)->where('erp_items_warehouses_amount',">",0) as $row)
                                        <option value="{{ $row->erp_items_id }}">{{ $row->erp_items_name ." [".$row->erp_items_warehouses_amount."]" }}</option>
                                    @endforeach
                                    <input wire:model.defer="finishProductModalData.use_item_3.amount" type="number" min="0" class="form-control @error('use_item_3.amount') is-invalid @enderror">
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
                                <select data-plugin-selectTwo class="form-control populate mb-1 @error('use_item_4.item_id') is-invalid @enderror" wire:model.defer="finishProductModalData.use_item_4.item_id">
                                    <option value="">Lütfen seçiniz...</option>
                                    @foreach($data->whereIn('erp_items_type',[0,1])->where('warehouses_id',23)->where('erp_items_warehouses_amount',">",0) as $row)
                                        <option value="{{ $row->erp_items_id }}">{{ $row->erp_items_name ." [".$row->erp_items_warehouses_amount."]" }}</option>
                                    @endforeach
                                    <input wire:model.defer="finishProductModalData.use_item_4.amount" type="number" min="0" class="form-control @error('use_item_4.amount') is-invalid @enderror">
                                </select>
                                @error('use_item_4.amount')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <div class="form-group">
                                    <label class="control-label" for="textareaAutosize">İşlem açıklaması</label>
                                    <textarea wire:model.defer="finishProductModalData.note" class="form-control" rows="2" id="textareaAutosize" data-plugin-textarea-autosize required></textarea>
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
        </div>
    @endif
    
    <script>
        window.addEventListener('{{ self::model }}startQualityControlModalShow', event => {
            $('#{{ self::model }}startQualityControlModal').modal('show');
        });
        window.addEventListener('{{ self::model }}startQualityControlModalHide', event => {
            $('#{{ self::model }}startQualityControlModal').modal('hide');
        });
        window.addEventListener('{{ self::model }}endQualityControlModalShow', event => {
            $('#{{ self::model }}endQualityControlModal').modal('show');
        });
        window.addEventListener('{{ self::model }}endQualityControlModalHide', event => {
            $('#{{ self::model }}endQualityControlModal').modal('hide');
        });
        window.addEventListener('{{ self::model }}finishProductModalShow', event => {
            $('#{{ self::model }}finishProductModal').modal('show');
        });
        window.addEventListener('{{ self::model }}finishProductModalHide', event => {
            $('#{{ self::model }}finishProductModal').modal('hide');
        });
        
    </script>

    {{-- middleware'den redirect yiyince - parts.alert - çalışmıyor --}}
    {{-- @include('parts.alert') --}}
    {{-- middleware'den redirect yiyince - parts.alert - çalışmıyor --}}
    @include('parts.alertify')

</div>
