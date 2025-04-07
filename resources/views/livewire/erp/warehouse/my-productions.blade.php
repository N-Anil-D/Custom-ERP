<div>

    @if($productions->count() > 0)
        <div class="row">
            <div class="col mb-3">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title">Tamamlanmamış üretimler</h2>
                        <p class="card-subtitle mb-2">Yarım bıraktığınız üretimler</p>

                        <div class="table-responsive" style="min-height: 300px;">
                            <table class="table table-responsive-md table-hover table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th class="center">#</th>
                                        <th>Üretim tanımı</th>
                                        <th>Üretilecek ürün kodu</th>
                                        <th>Üretilecek ürün adı</th>
                                        <th>Üretilecek stok yeri</th>
                                        <th class="center">Üretimde hedeflenen adet</th>
                                        <th>Başlama tarihi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($productions as $production)
                                        <tr>
                                            <td class="center">
                                                <a href="{{ route('create.production',[Auth::user()->id, $production->item_id, $production->warehouse_id, $production->id]) }}" class="btn btn-primary btn-xs">Üretime Devam Et</a>
                                                <a href="#" wire:click='cancelProductionCheck({{ $production->id }})' class="btn btn-danger btn-xs">Üretim Kaydı İptal</a>
                                            </td>
                                            <td>{{ $production->name }}</td>
                                            <td>{{ $production->item->code }}</td>
                                            <td>{{ $production->item->name }}</td>
                                            <td>{{ $production->warehouse->name }}</td>
                                            <td class="center">{{ $production->amount }} {{ $production->item->itemToUnit->code }}</td>
                                            <td>{{ $production->created_at }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col">
            <section class="card">

                <header class="card-header">
                    <div class="card-actions">
                        <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                        <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
                    </div>
                    <h2 class="card-title">Üretim işlemlerim</h2>
                    <p class="card-subtitle">Üretimini yaptığınız ürün/yarı mamül maddesini seçerek üretim kaydı oluşturunuz.</p>
                    <p class="card-subtitle">Toplam {{ $data->total() }} kayıttan {{ $data->count() }} adet listeleniyor.</p>
                </header>

                <div class="card-body">

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
                                    <th class="center">Stok miktarı</th>
                                    <th>Birimi</th>
                                    <th>Ürün tipi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $row)
                                    <tr>
                                        <td class="center">
                                            <button wire:click="openCreateProductionModal({{ $row->erp_items_id }}, {{ $row->warehouses_id }})" class="btn btn-primary btn-xs">Yeni üretim kaydı</button>
                                        </td>
                                        <td>{{ $row->erp_items_code }}</td>
                                        <td>{{ $row->erp_items_name }}</td>
                                        <td>{{ $row->erp_warehouses_name }}</td>
                                        <td class="center">{{ $row->erp_items_warehouses_amount }}</td>
                                        <td>{{ $row->erp_units_content }}</td>
                                        <td>{{ $row->getType() }}</td>
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

    <div class="row mt-3">
        <div class="col">
            <section class="card">

                <header class="card-header">
                    <div class="card-actions">
                        <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                        <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
                    </div>
                    <h2 class="card-title mb-2">{{ \Carbon\Carbon::today()->locale('tr')->dayName  }}</h2>
                    <h2 class="card-title mb-2">{{ \Carbon\Carbon::today()->format("d-m-Y") }}</h2>
                    <h2 class="card-title">Tarihili Tamamlanmış Üretimler</h2>
                    <p class="card-subtitle">Gün içerisinde tamamlanmış üretim kayıtları.</p>
                    <p class="card-subtitle">Toplam {{ $data->total() }} kayıttan {{ $data->count() }} adet listeleniyor.</p>
                </header>

                <div class="card-body">

                    {{-- <div class="col-lg-12 col-sm-12 pb-sm-3 pb-md-0 mb-2">
                        <input wire:model="search" type="search" class="form-control" placeholder="aramak istediğiniz ürünün adını ya da diğer bilgilerini buraya yazınız.">
                    </div> --}}

                    <div class="table-responsive" style="min-height: 300px;">
                        <table class="table table-responsive-md table-hover table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th class="center">#</th>
                                    <th>Ürün kodu</th>
                                    <th>Ürün adı</th>
                                    <th>İş Emri No</th>
                                    <th>Depo</th>
                                    <th>Üretim Adı</th>
                                    <th>Miktar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data2 as $row)
                                    <tr>
                                        <td class="center">
                                            <button wire:click="reverseProductionModal({{ $row->id }})" class="btn btn-warning btn-xs">Bu Kaydı İptal Et</button>
                                        </td>
                                        <td>{{ $row->item->code }}</td>
                                        <td>{{ $row->item->name }}</td>
                                        <td>{{ $row->work_order_no }}</td>
                                        <td>{{ $row->warehouse->name }}</td>
                                        <td>{{ $row->name }}</td>
                                        <td>{{ $row->amount ." ". $row->item->itemToUnit->code }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {!! ($data2->count() < 4) ? '<br><br><br>' : '' !!}
                        <hr>
                        {{ $data2->links() }}
                    </div>

                </div>

            </section>
        </div>
    </div>

    {{-- modals --}}

    <div class="modal fade" id="{{ self::model }}createProductionModal" tabindex="-1" aria-labelledby="{{ self::model }}createProductionModalLabel"
        aria-hidden="true">
        <form autocomplete="off" wire:submit.prevent="addProduction">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Yeni üretim {{ ($item) ? ' - '.$item->name : '' }}</h5>
                        <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
   
                    <div class="modal-body">
   
                        <div class="mb-3">
                            <label class="form-label">Vardiya Saati | Personel Adı | Cihaz Adı (*)</label>
                            <input wire:model.defer="selectedArrayData.name" type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Belirlenen üretim adını giriniz..." required>
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Hedeflenen üretim miktarı (*)</label>
                            @if (isset($item))
                            <input wire:model.defer="selectedArrayData.amount" type="number" step="0.0001" min="0" class="form-control @error('amount') is-invalid @enderror"
                            placeholder="{{ ($item) ? $item->itemToUnit->content.' ' : '' }}cinsinden değeri" required>
                            @endif
                            @error('amount')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
   
                        {{-- <div class="mb-3">
                            <label class="form-label">Lot Numarası</label>
                            @if (isset($item))
                            <input wire:model.defer="selectedArrayData.lot_no" type="text" class="form-control @error('lot_no') is-invalid @enderror">
                            @endif
                            @error('lot_no')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div> --}}
   
                        <div class="mb-3">
                            <label class="form-label">İş emri Numarası</label>
                            @if (isset($item))
                            <input wire:model.defer="selectedArrayData.work_order_no" type="text" class="form-control @error('work_order_no') is-invalid @enderror">
                            @endif
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
                </div>
            </div>
        </form>
    </div>
    
    @if (!is_null($cancelProduction))
        <div class="modal fade" id="{{ self::model }}cancelProductionModal" tabindex="-1" aria-labelledby="{{ self::model }}cancelProductionModalLabel"
            aria-hidden="true">
            <form autocomplete="off" wire:submit.prevent="cancelProduction">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Üretim İptal : {{ ($productions->find($this->productionId)) ? $productions->find($this->productionId)->item->name : '' }}</h5>
                            <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
    
                        <div class="modal-body">
    
                            <div class="mb-3">
                                <p><strong>{{ $cancelProduction->item ? $cancelProduction->item->name : '' }}</strong> ürünün üretim kaydı iptal edilecektir.</p>
                                <div>
                                    <ul>
                                        <li>Üretim Adı : {{ $cancelProduction->name }}</li>
                                        <li>Üretim Hedefi : {{ $cancelProduction->amount }}</li>
                                        <li>Üretim Kaydı Oluşturan : {{ $cancelProduction->user ? $cancelProduction->user->name :'' }}</li>
                                        <li>Üretimin Ekleneceği Depo : {{ $cancelProduction->warehouse ? $cancelProduction->warehouse->name :'' }}</li>
                                    </ul>
                                </div>
                                <p>Onaylıyor musunuz?</p>
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
    
    @if (!is_null($reverseProduction) && !is_null($productionId))
        <div class="modal fade" id="{{ self::model }}reverseProductionModal" tabindex="-1" aria-labelledby="{{ self::model }}reverseProductionModalLabel"
            aria-hidden="true">
            <form autocomplete="off" wire:submit.prevent="reverseProduction">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Üretim Kaydı Geri Alma : {{ ($productions->find($this->productionId)) ? $productions->find($this->productionId)->item->name : '' }}</h5>
                            <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
    
                        <div class="modal-body">
                            <div class="mb-3">
                                <p><strong>Üretim işlemi geri döndürelecektir.</strong></p>
                                <div>
                                    <ul>
                                        <li>Üretim Adı : {{ $reverseProduction->name }}</li>
                                        <li>Üretim Kaydı Oluşturan : {{ $reverseProduction->user ? $reverseProduction->user->name :'' }}</li>
                                        <li>Üretimin Ekleneceği Depo : {{ $reverseProduction->warehouse ? $reverseProduction->warehouse->name :'' }}</li>
                                        <li class="text-warning">Fire miktarları geri döndürülmeyecektir.</li>
                                    </ul>
                                </div>
                                <div>
                                    <h3><strong class="text-danger">Depodan eksilecek ürün.</strong></h3>
                                    <h4 class="text-muted">{{ $reverseProduction->item->name . " - ". $reverseProduction->amount . $reverseProduction->item->itemToUnit->code }}</h4>
                                </div>
                                <div>
                                    <h3><strong class="text-success">Depoya eklenecek ürün.</strong></h3>
                                    @foreach ($reverseProductionContent as $row)
                                        <h4 class="text-muted">{{ $row->item->name . " - (". $row->amount." - ". $row->wastage . " = ".($row->amount-$row->wastage). $row->item->itemToUnit->code.")" }}</h4>
                                    @endforeach
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
    window.addEventListener('{{ self::model }}createProductionModalShow', event => {
        $('#{{ self::model }}createProductionModal').modal('show');
    });
    window.addEventListener('{{ self::model }}createProductionModalHide', event => {
        $('#{{ self::model }}createProductionModal').modal('hide');
    });

    window.addEventListener('{{ self::model }}cancelProductionModalShow', event => {
        $('#{{ self::model }}cancelProductionModal').modal('show');
    });
    window.addEventListener('{{ self::model }}cancelProductionModalHide', event => {
        $('#{{ self::model }}cancelProductionModal').modal('hide');
    });

    window.addEventListener('{{ self::model }}reverseProductionModalShow', event => {
        $('#{{ self::model }}reverseProductionModal').modal('show');
    });
    window.addEventListener('{{ self::model }}reverseProductionModalHide', event => {
        $('#{{ self::model }}reverseProductionModal').modal('hide');
    });
</script>

@include('parts.alert')
@include('parts.alertify')
