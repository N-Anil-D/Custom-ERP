<div>
    
    <div class="row">
        <div class="col">
            <section class="card {{ $kgsKimlikleri->count()==0 ? '':'card-collapse' }}">
                <header class="card-header">
                    <div class="card-actions">
                        <a href="#" class="card-action card-action-toggle text-white" data-card-toggle></a>
                        <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
                    </div>
                    <h2 class="card-title">KGS Kullanıcıları</h2>
                </header>

                <div class="card-body">

                    <a wire:click="exampleInsertUsers()" class="btn btn-primary btn-xs mb-2">Örnek KGS Kimlik Excel'i İndir [ <i class="fas fa-file-excel"></i> Excel ]</a>
                    <a wire:click="process(1,'insert')" class="btn btn-success btn-xs mb-2">KGS Kimliklerini Yükle [ <i class="fas fa-file-excel"></i> Excel ]</a>
                    
                    <div class="col-12 pb-sm-3 pb-md-0 mb-2 {{ $kgsKimlikleri->count()==0 ? 'd-none':'' }}">
                        <input wire:model="search" type="search" class="form-control" placeholder="İsim ile ara">
                    </div>

                    <div class="table-responsive" style="min-height: 160px;">
                        <table class="table table-responsive-md table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>KGS ID</th>
                                    <th>Ad Soyad</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kgsKimlikleri as $kisi)
                                    <tr>
                                        <td>{{ $kisi->kgs_id }}</td>
                                        <td>{{ $kisi->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div>
                            {!! ($kgsKimlikleri->count() < 4) ? '<br><br><br>' : '' !!}
                            <hr>
                            {{ $kgsKimlikleri->links() }}
                        </div>

                    </div>

                </div>

            </section>
        </div>
    </div>
    <div class="modal fade" id="importKgsUsers" tabindex="-1" aria-labelledby="importKgsUsersLabel" aria-hidden="true" wire:ignore.self>
        <form autocomplete="off" wire:submit.prevent="importUsers">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        
                            <h5 class="modal-title">{{ trans('site.modal.header.'.$action) }}</h5>
                        <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        
                        <div class="mb-3">
                            <label class="form-label">KGS Kullanıcı Excel</label>
                            <div class="input-append">
                                <span class="btn btn-default btn-file">
                                    <input type="file" wire:model.defer="kgsUser.file" class="form-control @error('file') is-invalid @enderror" />
                                </span>
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

    <script>
        window.addEventListener('importKgsUserShow', event => {
            $('#importKgsUsers').modal('show');
        });
        window.addEventListener('importKgsUserHide', event => {
            $('#importKgsUsers').modal('hide');
        });

    </script>

    @include('parts.alert')
    
    
</div>
