<div>

    <div class="row mb-3">
        <div class="col">
            <section class="card">

                <header class="card-header">
                    <div class="card-actions">
                        <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                        <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
                    </div>
                    <h2 class="card-title">Stok sayım işlemi</h2>
                    <p class="card-subtitle">ürün kodu / depo bilgisi / miktar girilerek kayıt sağlanır.</p>
                </header>

                <div class="card-body">

                    <form autocomplete="off" wire:submit.prevent="addStockTaking">

                        <div class="form-group row pb-2">

                            <div class="col-lg-12 mb-3">
                                <select wire:model.defer="form.item_id" id="select2" data-plugin-selectTwo class="form-control populate @error('item_id') is-invalid @enderror" data-plugin-options='{ "minimumInputLength": 3 }'>
                                    <option>Lütfen ürün kodunu ya da adını giriniz...</option>
                                    @foreach ($items as $item)
                                        <option value="{{ $item->id }}">{{ $item->code }} - {{ $item->name }} - (birim : {{ $item->itemToUnit->content }})</option>
                                    @endforeach
                                </select>

                                @error('item_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                 @enderror
                            </div>

                            <div class="col-lg-12 mb-3">
                                <select wire:model.defer="form.warehouse_id" data-plugin-selectTwo class="form-control populate @error('warehouse_id') is-invalid @enderror">
                                    <option>Lütfen depo kodunu ya da adını giriniz...</option>
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->code }} - {{ $warehouse->name }} - {{ $warehouse->content }}</option>
                                    @endforeach
                                </select>
                                @error('warehouse_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-lg-12 mb-3">
                                <input wire:model.defer="form.amount" type="number" step="0.0001" min="0" class="form-control @error('amount') is-invalid @enderror" placeholder="Miktar giriniz.">
                                @error('amount')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-lg-12 mb-3">
                                <button type="submit" class="btn btn-primary">{{ trans('site.modal.button.save') }}</button>
                            </div>
                        </div>

                    </form>


                </div>
            </section>
        </div>
    </div>

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
                <div class="row">
                    <div class="col">
                        <section class="card">
                            <div class="card-body">
                                <div class="table-responsive" style="min-height: 300px;">
                                    <table class="table table-responsive-md table-hover table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Ürün kodu</th>
                                                <th>Ürün adı</th>
                                                <th>Depo kodu</th>
                                                <th>Depo adı</th>
                                                <th class="center">Sayım miktarı</th>
                                                <th>Sayımı yapan</th>
                                                <th>Durum</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $row)
                                                <tr>
                                                    <td>
                                                        <div class="btn-group flex-wrap">
                                                            <a style="text-decoration: none;" href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                                                                # {{ $row->id }} 
                                                                <span class="caret"></span>
                                                            </a>
                                                            <div class="dropdown-menu" role="menu">
                                                                <button wire:click="process({{ $row->id }}, 'delete')" class="dropdown-item text-1">
                                                                    {!! trans('site.button.delete') !!}
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $row->item->code }}</td>
                                                    <td>{{ $row->item->name }}</td>
                                                    <td>{{ $row->warehouse->code }}</td>
                                                    <td>{{ $row->warehouse->name }}</td>
                                                    <td class="center">{{ $row->amount }} {{ $row->item->itemToUnit->code }}</td>
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
                        <button class="btn btn-warning btn-sm" wire:click="process(0, 'confirm')">Sayımı bitir ve onaya gönder</button>
                    </div>
                </div>
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

    {{-- modal confirm --}}
    <div class="modal fade" id="{{ self::model.'confirmmodal' }}" tabindex="-1" aria-labelledby="{{ self::model.'confirmmodalLabel' }}"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        İşlemi onaylayınız
                    </h5>
                    <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Sayım verileri sorumlu kişiye onaylanması için gönderilecek.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('site.modal.button.cancel') }}</button>
                    <button wire:click="confirm" type="button" class="btn btn-primary">{{ trans('site.modal.button.confirm') }}</button>
                </div>
            </div>
        </div>
    </div>

    

    <script>
        $(document).ready(function () {
            $('#select2').select2();
            $('#select2').on('change', function (e) {
                var data = $('#select2').select2("val");
            @this.set('form.item_id', data);
            });
        });

        window.addEventListener('{{ self::model }}deletemodalShow', event => {
            $('#{{ self::model }}deletemodal').modal('show');
        });
        window.addEventListener('{{ self::model }}deletemodalHide', event => {
            $('#{{ self::model }}deletemodal').modal('hide');
        });
        window.addEventListener('{{ self::model }}confirmmodalShow', event => {
            $('#{{ self::model }}confirmmodal').modal('show');
        });
        window.addEventListener('{{ self::model }}confirmmodalHide', event => {
            $('#{{ self::model }}confirmmodal').modal('hide');
        });
    </script>

    @include('parts.alert')
    @include('parts.alertify')

</div>
