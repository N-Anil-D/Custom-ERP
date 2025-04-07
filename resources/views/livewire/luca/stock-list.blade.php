<div>
    <div class="row">
        <div class="col">
            <section class="card">
                <header class="card-header">
                    <div class="card-actions">
                        <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                        <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
                    </div>
                    <h2 class="card-title">Stok listesi</h2>
                    <p class="card-subtitle">Toplam {{ $data->total() }} üründen {{ $data->count() }} adet listeleniyor.</p>
                </header>
                <div class="card-body">

                    <div class="row mb-2 col-lg-4">
                        <div>
                            <a wire:click="downloadExcel" class="btn btn-default btn-sm btn-block">
                                <i class="fa fa-download"></i>
                                Tüm listeyi excel e aktar
                                <i class="far fa-file-excel"></i>
                            </a>
                        </div>
                    </div>


                    <div class="row form-group mb-2">
                        <div class="col-lg-9 col-sm-9 pb-sm-3 pb-md-0 mb-2">
                            <input wire:model="search" type="search" class="form-control" placeholder="aramak istediğiniz ürünün kod ya da adını buraya yazınız">
                        </div>

                        <div class="col-lg-3 col-sm-3 pb-sm-3 pb-md-0">
                            {{ $data->links() }}
                        </div>

                    </div>

                    <div class="table-responsive" style="min-height: 160px;">
                        <table class="table table-bordered table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Stok kodu</th>
                                    <th>Stok adı</th>
                                    <th class="text-center">Stok Miktarı</th>
                                    <th>Stok Türü</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $row)
                                <tr>
                                    <td class="text-center">
                                        @if(Auth::user()->usrToAlt->where('itemId',$row->id)->where('warned',FALSE)->count()>0)
                                        <a href="#" wire:click="process({{ $row->id }}, 'delete')">
                                        <i style="color:orange;" class='bx bxs-bell-off bx-xs'></i>
                                        </a>
                                        @endif
                                    </td>
                                    <td>

                                        <div class="btn-group flex-wrap">
                                            <a style="text-decoration: none;" href="#" class="dropdown-toggle" data-bs-toggle="dropdown">{{ $row->kartKodu }} <span class="caret"></span></a>
                                            <div class="dropdown-menu" role="menu">
                                                <button wire:click="process({{ $row->id }}, 'detail')" class="dropdown-item text-1">
                                                    <i style="color:green" class="fa fa-search"></i>
                                                    Detay göster
                                                </button>
                                                @if(Auth::user()->usrToAlt->where('itemId',$row->id)->where('warned',FALSE)->count()==0)
                                                <button wire:click="process({{ $row->id }}, 'alertMe')" class="dropdown-item text-1">
                                                    <i style="color:orange" class='bx bxs-bell-ring bx-tada bx-xs' ></i>
                                                    Beni uyar !
                                                </button>
                                                @endif
                                            </div>
                                        </div>

                                    </td>
                            
                                    <td>{{ $row->kartAdi }}</td>
                                    <td class="text-center">{{ $row->toplam }}</td>
                                    <td>{{ $row->stkToTyp->name }}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <hr>
                    {{ $data->links() }}
                </div>

            </section>
        </div>
    </div>


    <div class="modal fade" id="stockModal" tabindex="-1" aria-labelledby="stockModalLabel"
         aria-hidden="true" wire:ignore.self>
        <form autocomplete="off" wire:submit.prevent="insertOrUpdate">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">
                        @if($process == 'alertMe')
                        <i style="color:orange" class='bx bxs-bell-ring bx-tada bx-md' ></i>
                        Ürün Bilgilendirme Alarmı Kur
                        @else
                        Ürün bilgileri
                        @endif
                    </h3>
                    <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    @if($process == 'alertMe')

                    <p>
                        @if($item)
                        <strong>{{ $item->kartKodu }} - {{ $item->kartAdi }} ürünü için uyarı ver.</strong>
                        <br>
                        <span>Şuanki stok : {{ $item->toplam }}</span>
                        @endif
                    </p>
                    
                    <div class="mb-3">
                        <input wire:model.defer="alert.amount" type="number" step="0.0001" min="0" class="form-control @error('amount') is-invalid @enderror" placeholder="Adet Giriniz">
                        @error('amount')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <select wire:model="alert.alertCondition" class="form-control" aria-label="Default select example">
                        <option value='<'>Altına düştüğünde</option>
                        <option value='>'>Üzerine çıktığında</option>
                    </select>

                    @else

                    <div class="table-responsive">
                        <table class="table table-responsive-md table-hover table-bordered mb-0">

                            <tbody>
                                @if($item)
                                @if(Auth::user()->usrToAlt->where('itemId',$item->id)->where('warned',FALSE)->count()>0)
                                <tr>
                                    <td>
                                        <strong>
                                        <i style="color:orange" class='bx bxs-bell-ring bx-tada' ></i>
                                        Alarm Bilgisi
                                        </strong>
                                    </td>
                                    <td>
                                        @php
                                        $alertModalVar = Auth::user()->usrToAlt->where('itemId',$item->id)->where('warned',FALSE)->first();
                                        @endphp
                                        <strong>
                                        {{ $alertModalVar->amount }}
                                        {{ ($alertModalVar->alertCondition == '>') ? 'Üzerine Çıktığında' : 'Altına Düştüğünde' }}
                                        </strong>
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td>Ürün Kodu</td>
                                    <td>{{ $item->kartKodu }}</td>
                                </tr>
                                <tr>
                                    <td>Ürün Adı</td>
                                    <td>{{ $item->kartAdi }}</td>
                                </tr>
                                <tr>
                                    <td>Aktif</td>
                                    <td>{{ $item->aktif }}</td>
                                </tr>
                                <tr>
                                    <td>Ürün Tipi</td>
                                    <td>{{ $item->stkToTyp->name }}</td>
                                </tr>
                                <tr>
                                    <td>Toplam Giriş</td>
                                    <td>{{ $item->borcMiktar }}</td>
                                </tr>
                                <tr>
                                    <td>Toplam Çıkış</td>
                                    <td>{{ $item->alacakMiktar }}</td>
                                </tr>
                                <tr>
                                    <td>Stok</td>
                                    <td>
                                        <strong>
                                            {{ $item->toplam }}
                                        </strong>
                                    </td>
                                </tr>                                
                                @endif
                                
                            </tbody>

                        </table>
                    </div>

                    @endif 


                </div>

                
                <div class="modal-footer">
                    @if($process == 'alertMe')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('site.modal.button.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ trans('site.modal.button.confirm') }}</button>
                    @endif
                </div>
            </div>
        </div>
        </form>
    </div>
    
    
    {{-- modal delete --}}
    <div class="modal fade" id="alertDeleteModal" tabindex="-1" aria-labelledby="alertDeleteModalLabel"
        aria-hidden="true">
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
                    <button wire:click="delete" type="button" class="btn btn-primary">{{ trans('site.modal.button.confirm') }}</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('stockModalShow', event => {
            $('#stockModal').modal('show');
        });
        window.addEventListener('stockModalHide', event => {
            $('#stockModal').modal('hide');
        });
        window.addEventListener('alertDeleteModalShow', event => {
            $('#alertDeleteModal').modal('show');
        });
        window.addEventListener('alertDeleteModalHide', event => {
            $('#alertDeleteModal').modal('hide');
        });
    </script>

    @include('parts.alert')

</div>