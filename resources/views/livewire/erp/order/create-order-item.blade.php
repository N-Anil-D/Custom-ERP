<div>

    <div class="card">
        <div class="card-body">
        <div class="row">
                <div class="toggle toggle-primary" data-plugin-toggle>
                    <section class="toggle">
                        <label>Sipariş genel bilgileri</label>
                        <div class="toggle-content">
                            <table class="table table-responsive-md table-hover table-bordered mb-0 center">
                                <tr>
                                    <td>Sipariş no</td>
                                    <td>{{ $orderData->order_number }}</td>
                                </tr>
                                <tr>
                                    <td>Müşteri</td>
                                    <td>{{ $orderData->customer_name }}</td>
                                </tr>
                                <tr>
                                    <td>Sipariş tarihi</td>
                                    <td>{{ $orderData->order_date }}</td>
                                </tr>
                                <tr>
                                    <td>Teslim tarihi</td>
                                    <td>{{ $orderData->delivery_date }}</td>
                                </tr>
                                <tr>
                                    <td>Sipariş notu</td>
                                    <td>{{ $orderData->order_note }}</td>
                                </tr>
                            </table>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <div class="card-body">
        <div class="row">
                <div class="col-6">
                    <div class="table-responsive">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Ürün kodu</th>
                                        <th>Ürün adı</th>
                                        <th>Sipariş adeti</th>
                                        {{-- <th class="center">Son değişiklik tarihi</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orderItemData as $row)
                                        <tr>
                                            <td>
                                                <div class="btn-group flex-wrap">
                                                    <a style="text-decoration: none;" href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                                                        # {{ $row->id }} 
                                                        <span class="caret"></span>
                                                    </a>
                                                    <div class="dropdown-menu" role="menu">
    
                                                        <button wire:click="process({{ $row->id }}, 'update')" class="dropdown-item text-1">
                                                            {!! trans('site.button.update') !!}
                                                        </button>
                                                        
                                                        <button wire:click="process({{ $row->id }}, 'delete')" class="dropdown-item text-1">
                                                            {!! trans('site.button.delete') !!}
                                                        </button>
                                                       
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $row->item->code }}</td>
                                            <td>{{ $row->item->name }}</td>
                                            <td>{{ $row->amount }}</td>
                                            {{-- <td class="center">{{ $row->updated_at->format('Y-m-d H:i') }}</td> --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="col-lg-12 col-sm-12 pb-sm-3 pb-md-0 mb-2">
                        <input wire:model="searchItem" type="search" class="form-control" placeholder="aramak istediğiniz ürünün adını ya da diğer bilgilerini buraya yazınız.">
                    </div>
                    <div class="table-responsive">
                        <table class="table table-responsive-md table-hover table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th class="center">#</th>
                                    <th>Ürün kodu</th>
                                    <th>Ürün adı</th>
                                    <th>Birim</th>
                                    <th class="center">Toplam stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($itemData as $row)
                                    <tr>
                                        <td class="center">
                                            <button wire:click="addProductToOrder({{ $row->id }})" class="btn btn-primary btn-xs">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </td>
                                        <td>{{ $row->code }}</td>
                                        <td>{{ $row->name }}</td>
                                        <td>{{ $row->itemToUnit->content }}</td>
                                        <td class="center">{{ $row->stocks->sum('amount') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- modals --}}
    
    @if($item)
        <div class="modal fade" id="addProductToOrderModal" tabindex="-1" aria-labelledby="addProductToOrderModalLabel"
            aria-hidden="true">
            <form autocomplete="off" wire:submit.prevent="addProductToOrderConfirm">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $item->name }}</h5>
                            <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
       
                        <div class="modal-body">
       
                            <div class="mb-3">
                                <label class="form-label">Sipariş adeti</label>
                                <input wire:model.defer="selectedArrayData.amount" type="number" step="0.0001" min="0" class="form-control @error('amount') is-invalid @enderror"
                                    placeholder="Sipariş adeti giriniz">
                                @error('amount')
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

     {{-- modal insert or update --}}
     <div class="modal fade" id="{{ self::model.'upsertmodal' }}" tabindex="-1" aria-labelledby="{{ self::model.'upsertmodalLabel' }}"
     aria-hidden="true" wire:ignore.self>
     <form autocomplete="off" wire:submit.prevent="upsert">
         <div class="modal-dialog">
             <div class="modal-content">
                 <div class="modal-header">
                     <h5 class="modal-title">{{ trans('site.modal.header.'.$action) }}</h5>
                     <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                 </div>

                 <div class="modal-body">

                     <div class="mb-3">
                         <label class="form-label">Sipariş adeti</label>
                         <input wire:model.defer="selectedArrayData.amount" type="number" step="0.0001" min="0" class="form-control @error('amount') is-invalid @enderror"
                             placeholder="Sipariş adeti giriniz">
                         @error('amount')
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


</div>

</div>


<script>
    window.addEventListener('addProductToOrderModalShow', event => {
        $('#addProductToOrderModal').modal('show');
    });
    window.addEventListener('addProductToOrderModalHide', event => {
        $('#addProductToOrderModal').modal('hide');
    });


    window.addEventListener('{{ self::model }}deletemodalShow', event => {
        $('#{{ self::model }}deletemodal').modal('show');
    });
    window.addEventListener('{{ self::model }}deletemodalHide', event => {
        $('#{{ self::model }}deletemodal').modal('hide');
    });
    window.addEventListener('{{ self::model }}upsertmodalShow', event => {
        $('#{{ self::model }}upsertmodal').modal('show');
    });
    window.addEventListener('{{ self::model }}upsertmodalHide', event => {
        $('#{{ self::model }}upsertmodal').modal('hide');
    });
</script>

@include('parts.alert')
