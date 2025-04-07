<div>
    
    <div class="row">
        <div class="col">
            <section class="card">
                <header class="card-header">
                    <div class="card-actions">
                        <a wire:click="refresh" href="#" class="card-action fas fa-sync"></a>
                        <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                        <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
                    </div>
                    <h2 class="card-title">{{ $main->content }}</h2>
                    <p class="card-subtitle"></p>
                </header>
                <div class="card-body">
                    <a wire:click="process(0,'insert')" class="btn btn-primary btn-xs mb-2">
                        {!! trans('site.button.insert') !!}
                    </a>

                    <div class="table-responsive">
                        <table class="table table-responsive-md table-hover table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th class="center" colspan="2">#</th>
                                    <th>Madde adı</th>
                                    <th class="center" colspan="2">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($main->secToDet as $row)
                                <tr>
                                    
                                    <td class="center">{{ $row->id }}</td>
                                    <td class="center">

                                        @switch($row->type)
                                            @case(0)
                                                <span style="color: red;"><i class="fas fa-times"></i></span>
                                                @break
                                            @case(1)
                                                <span style="color: greenyellow;"><i class="fas fa-check"></i></span>
                                                @break
                                            @default
                                        @endswitch

                                    </td>
                                    <td>{{ $row->content }}</td>
                                    <td class="center">
                                        <button wire:click="process({{$row->id}},'edit')" class="btn btn-primary btn-xs">
                                            <i class='bx bx-edit-alt' ></i>
                                        </button>
                                    </td>
                                    <td class="center">
                                        <button wire:click="process({{$row->id}},'delete')" class="btn btn-danger btn-xs">
                                            <i class='bx bxs-trash'></i>
                                        </button>
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
    <div class="modal fade" id="rdmDeleteModal" tabindex="-1" aria-labelledby="rdmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ trans('site.modal.header.'.$action) }}</h5>
                    <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">{!! trans('site.modal.deleteinfo') !!}</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('site.modal.button.cancel') }}</button>
                    <button wire:click="delete" class="btn btn-primary">Onayla</button>
                </div>
            </div>
        </div>
    </div>

    {{-- modal insert or update --}}
    <div class="modal fade" id="rdmInsertOrUpdateModal" tabindex="-1" aria-labelledby="rdmInsertOrUpdateModalLabel" aria-hidden="true" wire:ignore.self>
        <form autocomplete="off" wire:submit.prevent="insertOrUpdate">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    
                        <h4 class="modal-title">{{ trans('site.modal.header.'.$action) }}</h4>
                    <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    
                    <div class="mb-3">
                        <label class="form-label">İçerik</label>
                        <input wire:model.defer="sub.content" type="text" class="form-control @error('content') is-invalid @enderror">
                        @error('content')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tip</label>
                        <select wire:model.defer="sub.type" class="form-control mb-3">
                            <option value="0" selected>Devam ediyor</option>
                            <option value="1">Tamamlandı</option>
                            <option value="2">Bilgilendirme</option>
                        </select>
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
        window.addEventListener('rdmDeleteModalShow', event => {
            $('#rdmDeleteModal').modal('show');
        });
        window.addEventListener('rdmDeleteModalHide', event => {
            $('#rdmDeleteModal').modal('hide');
        });

        window.addEventListener('rdmInsertOrUpdateModalShow', event => {
            $('#rdmInsertOrUpdateModal').modal('show');
        });
        window.addEventListener('rdmInsertOrUpdateModalHide', event => {
            $('#rdmInsertOrUpdateModal').modal('hide');
        });
    </script>


</div>
