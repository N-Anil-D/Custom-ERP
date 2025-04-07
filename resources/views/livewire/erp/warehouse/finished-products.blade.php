<div>

    <div class="row">
        <div class="col">
            <section class="card">

                <header class="card-header">
                    <div class="card-actions">
                        <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                        <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
                    </div>
                    <h2 class="card-title">Bitmiş Ürünler</h2>
                    <p class="card-subtitle"></p>
                    <p class="card-subtitle">Toplam {{ $data->total() }} kayıttan {{ $data->count() }} adet listeleniyor.</p>
                </header>
                
                <div class="card-body">
                    <div class="col-lg-12 col-sm-12 pb-sm-3 pb-md-0 mb-2">
                        <input wire:model="search" type="search" class="form-control" placeholder="LOT ile arama">
                    </div>

                    <div class="table-responsive" style="min-height: 300px;">
                        <table class="table table-responsive-md table-hover table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th class="center">#</th>
                                    <th>LOT</th>
                                    <th>Ürün adı</th>
                                    <th style="min-width: 185px;">Raf</th>
                                    <th>Stok yeri</th>
                                    <th class="center">Miktar</th>
                                    <th>Durum</th>
                                    <th style="min-width: 100px;">Tarih</th>
                                    <th>Not</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $row)
                                    <tr>
                                        @if ($rowId == $row->id)
                                            <td class="d-flex justify-content-center">
                                                <button wire:click="asignLocation()" class="btn btn-primary btn-xs">{{ trans('site.modal.button.save') }}</button>
                                            </td>
                                            <td>{{ $row->lot_no }}</td>
                                            <td>{{ $row->item->name }}</td>
                                            <td colspan="6">
                                                <div class="row justify-content-between align-items-center">
                                                    <div class="col-lg-3">
                                                        <select class="form-control" data-plugin-multiselect data-plugin-options='{ "maxHeight": 200 }' wire:model.change="p1">
                                                            <option value="">-</option>
                                                            {{-- <option value="HM">HM</option> --}}
                                                            <option value="MM">MM</option>
                                                            <option value="AMB">AMB</option>
                                                            <option value="ORTHM">ORTHM</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <select class="form-control" data-plugin-multiselect data-plugin-options='{ "maxHeight": 200 }' wire:model.change="p2">
                                                            @if (isset($p1))
                                                                <option value="" selected>-</option>
                                                                @if ($p1 == "HM")
                                                                    @for ($i = 1; $i <= 66; $i++)
                                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                                    @endfor
                                                                @elseif ($p1 == "MM")
                                                                    <option value="I">İ</option>
                                                                    <option value="K">K</option>
                                                                    <option value="L">L</option>
                                                                    <option value="M">M</option>
                                                                    <option value="PALET">PALET</option>
                                                                @elseif ($p1 == "AMB")
                                                                    @for ($i = 1; $i <= 21; $i++)
                                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                                    @endfor
                                                                @elseif ($p1 == "ORTHM")
                                                                    @for ($i = 1; $i <= 20; $i++)
                                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                                    @endfor
                                                                @endif
                                                            @else
                                                                <option value="">-</option>
                                                                <option value="PALET">PALET</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <select class="form-control" data-plugin-multiselect data-plugin-options='{ "maxHeight": 200 }' wire:model.change="p3">
                                                            @if (isset($p2))
                                                                <option value="" selected>-</option>
                                                                @if ($p2 == "i")
                                                                    @for ($i = 1; $i <= 24; $i++)
                                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                                    @endfor
                                                                @elseif ($p2 == "k" || $p2 == "l")
                                                                    @for ($i = 1; $i <= 18; $i++)
                                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                                    @endfor
                                                                @elseif ($p2 == "m")
                                                                    @for ($i = 1; $i <= 51; $i++)
                                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                                    @endfor
                                                                @else
                                                                    <option value="1">1</option>
                                                                    <option value="2">2</option>
                                                                    <option value="3">3</option>
                                                                @endif
                                                            @else
                                                                <option value="">-</option>
                                                                <option value="TUM RAF">TUM RAF</option>
                                                                <option value="OFIS">OFIS</option>
                                                                <option value="KİMYASAL ODA">KİMYASAL ODA</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </td>
                                        @else
                                            <td class="center">
                                                <div class="btn-group flex-wrap">
                                                    <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-bs-toggle="dropdown">İşlemler <span class="caret"></span></button>
                                                    <div class="dropdown-menu" role="menu">
                                                        @if(Auth::user()->can_exit)
                                                            <button wire:click="openExitModal({{ $row->warehouse_id }},{{ $row->id }})" class="dropdown-item text-1">
                                                                <i class="fas fa-dolly-flatbed"></i>
                                                                Ürün çıkışı (satış)
                                                            </button>
                                                        @endif
                                                        <button wire:click="asignNewLocationData({{ $row->id }},{{ $row->item_id }})" class="dropdown-item text-1">
                                                            <i class="fas fa-map-marker-alt"></i>
                                                            Raf ata
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $row->lot_no }}</td>
                                            <td>{{ $row->item->name }}</td>
                                            <td>
                                                @foreach ($row->location($row->item_id) as $itemLocation)
                                                    <div class="d-flex flex-row justify-content-between {{ count($row->location($row->item_id)) ? 'mb-2':'' }}">
                                                        <div class="mr-auto">
                                                            {{ $itemLocation->p1 }}
                                                            {{ $itemLocation->p1 ? '-'.$itemLocation?->p2 : $itemLocation?->p2  }}
                                                            {{ $itemLocation->p2 ? '-'.$itemLocation?->p3 : $itemLocation?->p3  }}
                                                        </div>  
                                                        @if (isset($itemLocation->p1) || isset($itemLocation->p2) || isset($itemLocation->p3))
                                                            <div class="">
                                                                <a class="btn btn-xs btn-warning my-lg-0 my-1" wire:click="editExistingLocation({{ $itemLocation->id.','.$row->id }})"><i class="far fa-edit"></i></a>
                                                                <a class="btn btn-xs btn-danger my-lg-0 my-1" wire:click="deleteLocation({{ $itemLocation->id }})"><i class="far fa-times-circle"></i></a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </td>
                                            <td>{{ $row->warehouse->name }}</td>
                                            <td class="center">{{ $row->amount .' '. $row->item->itemToUnit->code }}</td>
                                            <td>{{ $row->getStatus() }}</td>
                                            <td>{{ $row->send_date }}</td>
                                            <td class="center"><a class="{{ $row->note == null || $row->note=="" ? 'hidden':'' }}" data-bs-toggle="tooltip" data-bs-placement="left" title="{{ $row->note }}" href="#">Not</a></td>
                                        @endif
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
  

    {{-- modals--}}
    {{-- exit modal --}}
    @if (isset($sellFinishedProduct))
        <div class="modal fade" id="{{ self::model.'openExitModal' }}" tabindex="-1" aria-labelledby="{{ self::model.'openExitModalLabel' }}" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-lg">
                <form autocomplete="off" wire:submit.prevent="exitRequest">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="fas fa-dolly-flatbed"></i>  {{ $sellFinishedProduct->item?->code.' - '. $sellFinishedProduct->item?->name }}</h5>
                            <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Müşteri adı</label>
                                <input wire:model.defer="exitRequest.send_to" type="text" class="form-control @error('send_to') is-invalid @enderror" placeholder="Müşteri tanımını giriniz...">
                                @error('send_to')
                                    <div class="invalid-feedback"></div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">LOT No</label>
                                <input wire:model.defer="exitRequest.lot" type="text" class="form-control @error('lot') is-invalid @enderror" placeholder="Çıkış LOT u giriniz...">
                                @error('lot')
                                    <div class="invalid-feedback"></div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Fatura veya İrsaliye</label>
                                <input wire:model.defer="exitRequest.fatura_irsaliye_no" type="text" class="form-control @error('fatura_irsaliye_no') is-invalid @enderror" placeholder="Fatura veya irsaliye giriniz...">
                                @error('fatura_irsaliye_no')
                                    <div class="invalid-feedback"></div>
                                @enderror
                            </div>
                            @if($sellFinishedProduct)
                                <div class="mb-3">
                                    <label class="form-label">Sipariş miktarı {{ '['.$sellFinishedProduct->item?->itemToUnit->content.']' }}</label>
                                    <input wire:model.defer="exitRequest.amount" type="number" step="0.0001" min="0" max="{{ $sellFinishedProduct->amount }}" class="form-control @error('amount') is-invalid @enderror"
                                        placeholder="Sipariş miktarı giriniz (ürünün kendi biriminden değerini giriniz...) {{ $sellFinishedProduct->item?->itemToUnit->content }}">
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="form-group row pb-3">
                                        <label class="form-label pt-2">Gönderilme Tarihi</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-calendar-alt"></i>
                                            </span>
                                            <input type="date" wire:model.defer="exitRequest.send_date" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="mb-3">
                                <div class="form-group">
                                    <label class="control-label" for="textareaAutosize">İşlem açıklaması</label>
                                    <textarea wire:model.defer="exitRequest.content" class="form-control" rows="3" id="textareaAutosize" data-plugin-textarea-autosize></textarea>
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
        </div>
    @endif

    <script>

        window.addEventListener('{{ self::model }}openExitModalShow', event => {
            $('#{{ self::model }}openExitModal').modal('show');
        });
        window.addEventListener('{{ self::model }}openExitModalHide', event => {
            $('#{{ self::model }}openExitModal').modal('hide');
        });


    </script>

    {{-- middleware'den redirect yiyince - parts.alert - çalışmıyor --}}
    {{-- @include('parts.alert') --}}
    {{-- middleware'den redirect yiyince - parts.alert - çalışmıyor --}}
    @include('parts.alertify')


</div>
