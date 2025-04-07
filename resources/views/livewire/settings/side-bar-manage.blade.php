<div>

    <div class="row">
        <div class="col">
            <section class="card">
                <header class="card-header">
                    <div class="card-actions">
                        <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                        <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
                    </div>
                    <h2 class="card-title">Side menü yönetimi</h2>
                    <p class="card-subtitle">Dikkat ! Bu sayfada yapacağınız işlemler programın genel akışını
                        etkilemektedir.</p>
                </header>

                <div class="card-body">

                    <a wire:click="process(0, 'insert')" class="btn btn-primary btn-xs mb-2">{!! trans('site.button.insert') !!}</a>
                    
                    <div class="col-lg-12 col-sm-12 pb-sm-3 pb-md-0 mb-2">
                        <input wire:model="search" type="search" class="form-control" placeholder="aramak istediğiniz menü adını ya da linkini buraya yazınız.">
                    </div>


                    <div class="table-responsive">
                        <table class="table table-responsive-md table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tanım</th>
                                    <th>Hedef</th>
                                    <th>Sıra</th>
                                    <th>Menu ID</th>
                                    <th colspan="3" class="center">İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sideBar as $row)
                                    <tr>
                                        <td>{{ $row->id }}</td>
                                        <td>
                                            <span style="font-size: 13px">{!! $row->icon !!}</span>
                                            {{ $row->name }}
                                        </td>
                                        <td>{{ $row->link }}</td>
                                        <td>{{ $row->line }}</td>
                                        <td>{{ $row->hid }}</td>
                                        <td class="actions-hover actions-fade center">
                                            <a wire:click="process({{ $row->id }}, 'update')">
                                                <i class="fas fa-pencil-alt fa-lg"></i>
                                            </a>
                                        </td>
                                        
                                        <td class="actions-hover actions-fade center">
                                            <a wire:click="process({{ $row->id }}, 'insert')">
                                                <i class='bx bx-subdirectory-left bx-sm'></i>
                                            </a>
                                        </td>
                                        
                                        <td class="actions-hover actions-fade center">
                                            <a wire:click="process({{ $row->id }}, 'delete')"
                                                class="delete-row">
                                                <i class="far fa-trash-alt fa-lg"></i>
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

    {{-- modal delete --}}
    <div class="modal fade" id="sideBarDeleteModal" tabindex="-1" aria-labelledby="sideBarDeleteModalLabel"
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
    <div class="modal fade" id="sideBarInsertOrUpdateModal" tabindex="-1" aria-labelledby="sideBarInsertOrUpdateModalLabel"
        aria-hidden="true" wire:ignore.self>
        <form autocomplete="off" wire:submit.prevent="insertOrUpdate">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('site.modal.header.'.$action) }}</h5>
                    <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Tanım</label>
                        <input wire:model.defer="data.name" type="text" class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Icon</label>
                        <input wire:model.defer="data.icon" type="text" class="form-control @error('icon') is-invalid @enderror">
                         @error('icon')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Link</label>
                        <input wire:model.defer="data.link" type="text" class="form-control @error('link') is-invalid @enderror">
                         @error('link')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sıra</label>
                        <input wire:model.defer="data.line" type="number" class="form-control @error('line') is-invalid @enderror">
                         @error('line')
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

    <script>
        window.addEventListener('sideBarDeleteModalShow', event => {
            $('#sideBarDeleteModal').modal('show');
        });
        window.addEventListener('sideBarDeleteModalHide', event => {
            $('#sideBarDeleteModal').modal('hide');
        });
        window.addEventListener('sideBarInsertOrUpdateModalShow', event => {
            $('#sideBarInsertOrUpdateModal').modal('show');
        });
        window.addEventListener('sideBarInsertOrUpdateModalHide', event => {
            $('#sideBarInsertOrUpdateModal').modal('hide');
        });
    </script>

    @include('parts.alert')
</div>
