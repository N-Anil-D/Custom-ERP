<div>

    <div class="row">
        <div class="col">
            <section class="card">

                <header class="card-header">
                    <div class="card-actions">
                        <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                        <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
                    </div>
                    <h2 class="card-title">Gönderilmiş Ürünler</h2>
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
                                    <th>Fatura & İrsaliye</th>
                                    <th>Ürün adı</th>
                                    <th>Son Stok yeri</th>
                                    <th>Son İşlem</th>
                                    <th>Gönderildi</th>
                                    <th class="center">Miktar</th>
                                    <th>Durum</th>
                                    <th>Tarih</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $row)
                                    <tr>
                                        <td class="center">
                                            <div class="btn-group flex-wrap">
												<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-bs-toggle="dropdown">İşlemler <span class="caret"></span></button>
												<div class="dropdown-menu" role="menu">
                                                    @if((Auth::user()->can_exit || Auth::user()->confirm_exit) && $row->status == 1)
                                                        <button wire:click="takeBackModal({{ $row->id }})" class="dropdown-item text-1">
                                                            <i class="fas fa-dolly-flatbed"></i>
                                                            Gönderimi geri çek
                                                        </button>
                                                    @endif
                                                    @if((Auth::user()->can_exit || Auth::user()->confirm_exit) && $row->status == 2)
                                                        <button wire:click="sendAgainModal({{ $row->id }})" class="dropdown-item text-1">
                                                            <i class="fas fa-dolly-flatbed"></i>
                                                            Tekrar gönder
                                                        </button>
                                                    @endif
												</div>
											</div>
                                        </td>
                                        <td>{{ $row->lot_no }}</td>
                                        <td>{{ $row->fatura_irsaliye_no }}</td>
                                        <td>{{ $row->item->name }}</td>
                                        <td>{{ $row->warehouse->name }}</td>
                                        <td>{{ $row->user->name }}</td>
                                        <td>{{ $row->send_to }}</td>
                                        <td class="center">{{ $row->amount .' '. $row->item->itemToUnit->code }}</td>
                                        <td class=" {{ $row->status == 1 ? 'bg-success text-white':'bg-warning text-dark' }}">{{ $row->getStatus() }}</td>
                                        <td>{{ $row->send_date }}</td>
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
    {{-- takeBack modal --}}
    @if (isset($sendProduct))
        <div class="modal fade" id="{{ self::model.'takeBackModal' }}" tabindex="-1" aria-labelledby="{{ self::model.'takeBackModalLabel' }}" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-lg">
                <form autocomplete="off" wire:submit.prevent="takeBack">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="fas fa-dolly-flatbed"></i>  {{ $sendProduct?->item->name }}</h5>
                            <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Geri çekilen ürün miktarı</label>
                                <input wire:model.defer="sendProductModalData.amount" type="number" step="0.0001" min="0" max="{{ $sendProduct->amount }}" class="form-control @error('amount') is-invalid @enderror"
                                    placeholder="Geri çekilen ürün miktarını giriniz {{ $sendProduct?->item->itemToUnit->content }}">
                                @error('content')
                                    <div class="invalid-feedback"></div>
                                @enderror
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

    {{-- takeBack modal --}}
    @if (isset($sendProduct))
        <div class="modal fade" id="{{ self::model.'sendAgainModal' }}" tabindex="-1" aria-labelledby="{{ self::model.'sendAgainModalLabel' }}" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-lg">
                <form autocomplete="off" wire:submit.prevent="sendAgain">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="fas fa-dolly-flatbed"></i>  {{ $sendProduct?->item->name }}</h5>
                            <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Tekrar gönderilen miktar</label>
                                <input wire:model.defer="sendProductModalData.amount" type="number" step="0.0001" min="0" max="{{ $sendProduct->amount }}" class="form-control @error('amount') is-invalid @enderror"
                                    placeholder="Geri çekilen ürün miktarını giriniz {{ $sendProduct?->item->itemToUnit->content }}">
                                @error('content')
                                    <div class="invalid-feedback"></div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Müşteri adı</label>
                                <input wire:model.defer="sendProductModalData.send_to" type="text" class="form-control @error('send_to') is-invalid @enderror" placeholder="Müşteri tanımını giriniz...">
                                @error('send_to')
                                    <div class="invalid-feedback"></div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <div class="form-group row pb-3">
                                    <label class="form-label pt-2">Gönderilme Tarihi</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </span>
                                        <input type="date" wire:model.defer="sendProductModalData.send_date" class="form-control">
                                    </div>
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

        window.addEventListener('{{ self::model }}takeBackModalShow', event => {
            $('#{{ self::model }}takeBackModal').modal('show');
        });
        window.addEventListener('{{ self::model }}takeBackModalHide', event => {
            $('#{{ self::model }}takeBackModal').modal('hide');
        });

        window.addEventListener('{{ self::model }}sendAgainModalShow', event => {
            $('#{{ self::model }}sendAgainModal').modal('show');
        });
        window.addEventListener('{{ self::model }}sendAgainModalHide', event => {
            $('#{{ self::model }}sendAgainModal').modal('hide');
        });


    </script>

    {{-- middleware'den redirect yiyince - parts.alert - çalışmıyor --}}
    {{-- @include('parts.alert') --}}
    {{-- middleware'den redirect yiyince - parts.alert - çalışmıyor --}}
    @include('parts.alertify')


</div>
