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
                    <h2 class="card-title">RoadMap</h2>
                    <p class="card-subtitle"></p>
                </header>

                <div class="card-body">

                    <a wire:click="process(0,'insert')" class="btn btn-primary btn-xs mb-2">{!! trans('site.button.insert') !!}</a>

                    <div class="col-lg-12 col-sm-12 pb-sm-3 pb-md-0 mb-2">
                        <input wire:model="search" type="search" class="form-control" placeholder="aramak alt hedefin tanımını buraya yazınız.">
                    </div>

                    <div class="table-responsive">
                        <table class="table table-responsive-md table-hover table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Liste adı</th>
                                    <th class="center">Düzenle</th>
                                    <th class="center">Alt madde işlemleri</th>
                                    <th class="center">Pasife al</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sections as $row)
                                <tr>
                                    
                                    <td style="{{ (!$row->active) ? 'color:red' : '' }}">{{ $row->line }}</td>
                                    <td style="{{ (!$row->active) ? 'color:red' : '' }}">{{ $row->content }}</td>
                                    <td class="center">
                                        <a wire:click="process({{$row->id}},'edit')" class="btn btn-primary btn-xs">
                                            <i class='bx bx-edit-alt' ></i>
                                        </a>
                                    </td>
                                    <td class="center">
                                        <a href="{{ route('set.subRoad',$row->id) }}" class="btn btn-primary btn-xs">
                                            <i class='bx bx-search-alt-2' ></i> Görüntüle
                                        </a>
                                    </td>
                                    <td class="center">
                                        <a wire:click="process({{$row->id}},'delete')" class="btn {{ ($row->active) ? 'btn-danger' : 'btn-success' }} btn-xs">
                                            @if($row->active)
                                                <i class='bx bxs-trash'></i>
                                            @else
                                                <i class='bx bx-check' ></i>
                                            @endif
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <hr>

                    </div>

                </div>

            </section>
        </div>
    </div>

    {{-- modal delete --}}
    @isset($section['active'])
    <div class="modal fade" id="roadMapSectionDeleteModal" tabindex="-1" aria-labelledby="roadMapSectionDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ ($section['active']) ? 'Pasif etme işlemini onaylayınız' : 'Aktife alma işlemini onaylayınız' }}
                    </h5>
                    <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if($section['active'])
                    <strong>Dikkat!
                        <br>
                        Bu işlem sonrası yol haritasında bu madde ve bu maddeye bağlı tüm alt maddeler pasif hale gelecektir.
                    </strong>
                    @else
                        Seçilen madde görünür hale gelecektir.
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('site.modal.button.cancel') }}</button>
                    <button wire:click="delete" class="btn btn-primary">Onayla</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- modal insert or update --}}
    <div class="modal fade" id="roadMapSectionInsertOrUpdateModal" tabindex="-1" aria-labelledby="roadMapSectionInsertOrUpdateModalLabel" aria-hidden="true" wire:ignore.self>
        <form autocomplete="off" wire:submit.prevent="insertOrUpdate">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    
                        <h4 class="modal-title">{{ trans('site.modal.header.'.$action) }}</h4>
                    <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    
                    <div class="mb-3">
                        <label class="form-label">Başlık</label>
                        <input wire:model.defer="section.content" type="text" class="form-control @error('content') is-invalid @enderror">
                        @error('content')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tip</label>
                        <select wire:model.defer="section.type" class="form-control mb-3">
                            <option value="0" selected>Devam ediyor</option>
                            <option value="1">Tamamlandı</option>
                            <option value="2">Bilgilendirme</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sıralama</label>
                        <input wire:model.defer="section.line" type="number" class="form-control @error('line') is-invalid @enderror">
                        @error('line')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Yazar / Görev üstlenici</label>
                        <input wire:model.defer="section.author" type="text" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Animasyon türü</label>
                        <select wire:model.defer="section.animation" class="form-control mb-3">
                            <option value="fadeInRight">Sağdan giriş</option>
                            <option value="fadeInLeft">Soldan giriş</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Animasyon hızı (milisaniye)</label>
                        <input wire:model.defer="section.animationDelay" type="number" class="form-control">
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
        window.addEventListener('roadMapSectionDeleteModalShow', event => {
            $('#roadMapSectionDeleteModal').modal('show');
        });
        window.addEventListener('roadMapSectionDeleteModalHide', event => {
            $('#roadMapSectionDeleteModal').modal('hide');
        });
        window.addEventListener('roadMapSectionInsertOrUpdateModalShow', event => {
            $('#roadMapSectionInsertOrUpdateModal').modal('show');
        });
        window.addEventListener('roadMapSectionInsertOrUpdateModalHide', event => {
            $('#roadMapSectionInsertOrUpdateModal').modal('hide');
        });
    </script>

    @include('parts.alert')



</div>
