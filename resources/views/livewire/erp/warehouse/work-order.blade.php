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
                    {{-- <p class="card-subtitle">Toplam {{ $workOrders->total() }} kayıttan {{ $workOrders->count() }} adet listeleniyor.</p> --}}
                </header>
                
                <div class="card-body">
                    @if (Auth::user()->work_order_level==2)
                        <button type="button" class="btn btn-primary btn-xs mb-2" wire:click="openModal('new')">
                            {!! trans('site.button.insert') !!}
                        </button>
                    @endif
                    <div class="col-lg-12 col-sm-12 pb-sm-3 pb-md-0 mb-2">
                        <input wire:model="search" type="search" class="form-control" placeholder="aramak istediğiniz ürünün adını ya da diğer bilgilerini buraya yazınız.">
                    </div>

                    <div class="table-responsive" style="min-height: 160px;">
                        <table class="table table-responsive-md table-hover table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th class="center">#</th>
                                    <th>İş Emrinin Adı</th>
                                    <th>Güncelleyen Kişi</th>
                                    <th>Güncelleme Tarihi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($workOrders as $workOrder)
                                    <tr>
                                        <td>
                                            <div class="btn-group flex-wrap">
                                                <a style="text-decoration: none;" href="#" class="dropdown-toggle" data-bs-toggle="dropdown">{{ $workOrder->id }}<span class="caret"></span></a>
                                                <div class="dropdown-menu" role="menu">
                                                    <button wire:click="downloadFile({{ $workOrder->id }})" class="dropdown-item text-1">
                                                        <i class="fas fa-file-download"></i>
                                                        İndir
                                                    </button>
                                                    @if (Auth::user()->work_order_level==2)
                                                    <button wire:click="openModal('edit',{{ $workOrder->id }})" class="dropdown-item text-1">
                                                        <i class="fas fa-edit"></i>
                                                        Güncelle
                                                    </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $workOrder->name }}</td>
                                        <td>{{ $workOrder->getUpdateUser->name }}</td>
                                        <td>{{ $workOrder->updated_at }}</td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                        {!! ($workOrders->count() < 4) ? '<br><br><br>' : '' !!}
                        <hr>
                        {{ $workOrders->links() }}
                    </div>

                </div>

            </section>
        </div>
    </div>


    {{-- modal WorkOrder New --}}
    <div class="modal fade" id="{{ self::model }}New" tabindex="-1" aria-labelledby="{{ self::model }}NewLabel" aria-hidden="true" wire:ignore.self>
        <form autocomplete="off" wire:submit.prevent="createNewWorkOrder" enctype="multipart/form-data">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Yeni iş emri ekle</h3>
                        <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="workOrderName" class="p-2 @error('workOrderName') is-invalid @enderror">İsim (opsiyonel) <span class="text-warning">Bu isim listlemede ve indirilen dosya isminde etkili olacaktır.</span></label>
                            <input wire:model.defer="newWorkOrder.name" type="text" id="workOrderName" class="form-control @error('workOrderName') is-invalid @enderror" placeholder="Boş bırakılırsa dosya adı geçerli olur.">
                            @error('workOrderName')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-control mb-3">
                            <label for="new" class="btn btn-primary p-2 form-control @error('file') is-invalid @enderror">[Excel] && [Limit 1 MB]</label>
                            <input wire:model.defer="newWorkOrder.file" type="file" id="new" class="d-none form-control @error('file') is-invalid @enderror">
                            @error('file')
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
            </div>
        </form>
    </div>

    {{-- modal WorkOrder Edit --}}
    @if($edit)
        <div class="modal fade" id="{{ self::model }}Edit" tabindex="-1" aria-labelledby="{{ self::model }}EditLabel" aria-hidden="true" wire:ignore.self>
            <form autocomplete="off" wire:submit.prevent="editWorkOrder" enctype="multipart/form-data">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title">İş Emri Düzenle</h3>
                            <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="workOrderName" class="p-2 @error('workOrderName') is-invalid @enderror">İsim (opsiyonel) <span class="text-warning">Bu isim listlemede ve indirilen dosya isminde etkili olacaktır.</span></label>
                                <input wire:model.defer="editWorkOrder.name" type="text" id="workOrderName" class="form-control @error('workOrderName') is-invalid @enderror" placeholder="Boş bırakılırsa dosya adı geçerli olur.">
                                @error('workOrderName')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-control mb-3">
                                <label for="file" class="btn btn-primary p-2 form-control @error('file') is-invalid @enderror">[Excel] && [Limit 1 MB]</label>
                                <input wire:model.defer="editWorkOrder.file" type="file" id="file" class="d-none form-control @error('file') is-invalid @enderror">
                                @error('file')
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
                </div>
            </form>
        </div>
    @endif

    <script>

        window.addEventListener('{{ self::model }}NewShow', event => {
            $('#{{ self::model }}New').modal('show');
        });
        window.addEventListener('{{ self::model }}NewHide', event => {
            $('#{{ self::model }}New').modal('hide');
        });

        window.addEventListener('{{ self::model }}EditShow', event => {
            $('#{{ self::model }}Edit').modal('show');
        });
        window.addEventListener('{{ self::model }}EditHide', event => {
            $('#{{ self::model }}Edit').modal('hide');
        });

    </script>
    
    @include('parts.alert')

</div>
