<div>
    
    {{-- content --}}
    <div>
        <section class="card mb-4">
            <div class="card-body">
                <p>Bu sayfa içerisinde size özel önemli verileri/parolaları güvenli bir şekilde saklayabilirsiniz.
                    Kayıt altına alınan veriler veritabanında şifrelenmiş bir şekilde tutulacak ve yanlızca sizin ihtiyacınız olduğunda erişilebilir hale gelecektir.
                </p>
                <div>
                    <a wire:click="process(0,'insert')" class="btn btn-primary btn-xs">{!! trans('site.button.insert') !!}</a>
                </div>

                <div class="col-lg-12 col-sm-12 pb-sm-3 pb-md-0 mb-2 mt-2">
                    <input wire:model="search" type="search" class="form-control" placeholder="aramak istediğiniz şifrelenmiş alanın adını buraya yazınız.">
                </div>
            </div>
        </section>

        @if($data->count()>0)
        <div class="row">
            <div class="col">
                <section class="card">
                    <header class="card-header">
                        <div class="card-actions">
                            <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                            <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
                        </div>
                        <h2 class="card-title">Parola Kutusu</h2>
                        <p class="card-subtitle"></p>
                    </header>
                    <div class="card-body">
                        

                        <div class="table-responsive">
                            <table class="table table-responsive-md table-hover table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Şifrelenmiş alan</th>
                                        <th class="center">İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $row)
                                    <tr>
                                        <td>#{{ Auth::user()->id }}{{ $row->id }}</td>
                                        <td>
                                                {{ $row->definition }}
                                        </td>
                                        <td class="center">
                                            <a wire:click="process({{ $row->id }}, 'view')" class="btn btn-primary btn-xs">
                                                <i class="fas fa-search"></i>
                                                Görüntüle / Düzenle
                                            </a> 
                                            <a wire:click="process({{ $row->id }}, 'delete')" class="btn btn-danger btn-xs">
                                                <i class="far fa-trash-alt"></i>
                                                Sil
                                            </a> 
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </section>
            </div>
        </div>
        @endif
        
    </div>

    {{-- modal insert or update --}}
    <div class="modal fade" id="psboxInsertOrUpdateModal" tabindex="-1" aria-labelledby="psboxInsertOrUpdateModalLabel"
        aria-hidden="true" wire:ignore.self>
        <form autocomplete="off" wire:submit.prevent="insertOrUpdate">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    
                        <h4 class="modal-title">
                            <i class='bx bxs-key bx-spin' ></i>
                            {{ trans('site.modal.header.'.$action) }}
                        </h4>
                    <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    
                    <div class="mb-3">
                        <label class="form-label">Şifrelenmiş alan tanımı</label>
                        <input wire:model.defer="psbox.definition" type="text" class="form-control @error('definition') is-invalid @enderror">
                        @error('definition')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Şifrelenmiş alan - 1</label>
                        <div class="input-group">
                            <input id="def1" wire:model.defer="psbox.def1" type="text" class="form-control @error('def1') is-invalid @enderror">
                            @if($action=='view')
                            <button onclick="copyElement(def1)" class="btn btn-default" type="button"><i class='bx bxs-copy'></i></button>
                            @endif
                            @error('def1')
                            <div class="invalid-feedback">
                                En az bir adet crypt edilecek veri girilmesi gerekir.
                            </div>
                            @enderror
                        </div>
                    </div>                   
                    <div class="mb-3">
                        <label class="form-label">Şifrelenmiş alan - 2</label>
                        <div class="input-group">
                            <input id="def2" wire:model.defer="psbox.def2" type="text" class="form-control">
                            @if($action=='view')
                                <button onclick="copyElement(def2)" class="btn btn-default" type="button"><i class='bx bxs-copy'></i></button>
                            @endif
                        </div>
                    </div>                   
            
                    
                    <div class="mb-3">
                        <label class="form-label">Şifrelenmiş alan - 3</label>
                        <div class="input-group">
                            <input id="def3" wire:model.defer="psbox.def3" type="text" class="form-control">
                            @if($action=='view')
                                <button onclick="copyElement(def3)" class="btn btn-default" type="button"><i class='bx bxs-copy'></i></button>
                            @endif
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

    {{-- modal delete --}}
    <div class="modal fade" id="psboxDeleteModal" tabindex="-1" aria-labelledby="psboxDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('site.modal.header.'.$action) }}</h5>
                    <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">{!! trans('site.modal.deleteinfo') !!}</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('site.modal.button.cancel') }}</button>
                    <a wire:click="delete" class="btn btn-primary">{{ trans('site.modal.button.confirm') }}</a>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        window.addEventListener('psboxDeleteModalShow', event => {
            $('#psboxDeleteModal').modal('show');
        });
        window.addEventListener('psboxDeleteModalHide', event => {
            $('#psboxDeleteModal').modal('hide');
        });
        window.addEventListener('psboxInsertOrUpdateModalShow', event => {
            $('#psboxInsertOrUpdateModal').modal('show');
        });
        window.addEventListener('psboxInsertOrUpdateModalHide', event => {
            $('#psboxInsertOrUpdateModal').modal('hide');
        });

        function copyElement($val){
            navigator.clipboard.writeText($val.value);
            new PNotify({
                    title: 'Kopyalandı',
                    text: '',
                    type: 'info'
                });
        }

    </script>
    

@include('parts.alert')
</div>
