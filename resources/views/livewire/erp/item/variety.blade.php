<div>

    <div class="row">
        <div class="col">
            <section class="card">
                <header class="card-header">
                    <div class="card-actions">
                        <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                        <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
                    </div>
                    <h2 class="card-title">Ürün Çeşitleri</h2>
                    {{-- <p class="card-subtitle">Dikkat ! Bu sayfada yapacağınız işlemler programın genel akışını etkilemektedir.</p> --}}
                    <p class="card-subtitle">Toplam {{ $data->total() }} kayıttan {{ $data->count() }} adet listeleniyor.</p>
                </header>

                <div class="card-body">

                    <a wire:click="process(0,'insert')" class="btn btn-primary btn-xs mb-2">
                        {!! trans('site.button.insert') !!}
                    </a>
                    {{-- <button type="button" class="btn btn-success btn-xs mb-2" wire:click="systemExports()">
                        {!! trans('site.excel.export') !!}
                    </button> --}}

                    <div class="col-lg-12 col-sm-12 pb-sm-3 pb-md-0 mb-2">
                        <input wire:model="search" type="search" class="form-control" placeholder="aramak istediğiniz birimin adını ya da kayıt tarihini buraya yazınız.">
                    </div>

                    <div class="table-responsive" style="min-height: 160px;">
                        <table class="table table-responsive-md table-hover table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>İsim</th>
                                    <th class="center">Kayıt tarihi</th>
                                    <th class="center">Son değişiklik tarihi</th>
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
                                                    <button wire:click="process({{ $row->id }}, 'update')" class="dropdown-item text-1">
                                                        {!! trans('site.button.update') !!}
                                                    </button>
                                                    <button wire:click="process({{ $row->id }}, 'delete')" class="dropdown-item text-1">
                                                        {!! trans('site.button.delete') !!}
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $row->name }}</td>
                                        <td class="center">{{ $row->updated_at->format('Y-m-d H:i') }}</td>
                                        <td class="center">{{ $row->created_at->format('Y-m-d H:i') }}</td>
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

                        @if($action == 'insert')
                        <div class="mb-3">
                            <label class="form-label">Yeni Ürün Çeşidi</label>
                            <input wire:model.defer="selectedArrayData.name" type="text" class="form-control @error('name') is-invalid @enderror"
                                placeholder="">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        @endif

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


</div>
