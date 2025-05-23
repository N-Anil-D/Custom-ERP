<div>

    <div class="row">
        <div class="col">
            <section class="card">
                <header class="card-header">
                    <div class="card-actions">
                        <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                        <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
                    </div>
                    <h2 class="card-title">Barkod listesi oluştur</h2>
                    <p class="card-subtitle"></p>
                    <p class="card-subtitle">Toplam {{ $brcList->total() }} listeden {{ $brcList->count() }} adet listeleniyor.</p>
                </header>

                <div class="card-body">

                    <a wire:click="process(0,'insert')" class="btn btn-primary btn-xs mb-2">{!! trans('site.button.insert') !!}</a>
                    <a href="{{ url('uretim/uretim_tube_barcodeTemplate_v2.xlsx') }}" class="btn btn-success btn-xs mb-2">
                        <i class="fa fa-download"></i>
                        Şablonu İndir
                    </a>
                    <div class="col-lg-12 col-sm-12 pb-sm-3 pb-md-0 mb-2">
                        <input wire:model="search" type="search" class="form-control" placeholder="aramak istediğiniz listenin adını ya da kayıt tarihini buraya yazınız.">
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-responsive-md table-hover table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Liste adı</th>
                                    <th class="center">Ürün sayısı</th>
                                    <th class="center">Kayıt tarihi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($brcList as $row)
                                <tr>
                                    <td>{{ $row->id }}</td>
                                    <td>
                                       
                                        
                                        <div class="btn-group flex-wrap">
                                            <a style="text-decoration: none;" href="#" class="dropdown-toggle" data-bs-toggle="dropdown">{{ $row->name }} <span class="caret"></span></a>
                                            <div class="dropdown-menu" role="menu">
                                                
                                                <a href="{{ route('uretim.barcode-tube.download',$row->id) }}" class="dropdown-item text-1">
                                                    <i class="fas fa-file-pdf fa-lg"></i>
                                                    PDF olarak indir
                                                </a> 
                                                <a href="{{ route('uretim.barcode-tube.see',$row->id) }}" target="_blank" class="dropdown-item text-1">
                                                    <i class="fas fa-search fa-lg"></i>
                                                    İncele
                                                </a>  
                                                
                                                <li><hr class="dropdown-divider"></li>
                                                
                                                 <a wire:click="process({{ $row->id }}, 'delete')" href="#" class="dropdown-item text-1">
                                                    <i class="far fa-trash-alt fa-lg"></i>
                                                    Sil
                                                </a> 
                                                
                                            </div>
                                        </div>
                            
                            
                                    </td>
                                    <td class="center">{{ $row->lisToLin->count() }}</td>
                                    <td class="center">{{ $row->created_at->format('Y-m-d H:i') }}</td>
                                   
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <hr>
                    {{ $brcList->links() }}
                    </div>
                    
                </div>

            </section>
        </div>
    </div>

    {{-- modal delete --}}
    <div class="modal fade" id="uretimTubeBrcListDeleteModal" tabindex="-1" aria-labelledby="brcListDeleteModalLabel"
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



    {{-- modal insert --}}
    <div class="modal fade" id="uretimTubeBrcListInsertModal" tabindex="-1" aria-labelledby="brcListInsertModalLabel"
         aria-hidden="true" wire:ignore.self>
        <form autocomplete="off" wire:submit.prevent="brcListInsert">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">

                        <h5 class="modal-title">{{ trans('site.modal.header.insert') }}</h5>
                        <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label">Liste adı</label>
                            <input wire:model.defer="listData.name" type="text" class="form-control" placeholder="Liste tanımlaması olarak geçerli bir metin giriniz." required>
                                @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                        </div>

                        <div class="mb-3">
                            <label for="formFile" class="form-label">Liste içeriği / Excel dosyası</label>
                            <input wire:model.defer="listData.excelFile" class="form-control @error('excelFile') is-invalid @enderror" type="file" id="formFile" accept=".xls, .xlsx" required>
                                @error('excelFile')
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

    <script>
        window.addEventListener('uretimTubeBrcListDeleteModalShow', event => {
            $('#uretimTubeBrcListDeleteModal').modal('show');
        });
        window.addEventListener('uretimTubeBrcListDeleteModalHide', event => {
            $('#uretimTubeBrcListDeleteModal').modal('hide');
        });
        window.addEventListener('uretimTubeBrcListInsertModalShow', event => {
            $('#uretimTubeBrcListInsertModal').modal('show');
        });
        window.addEventListener('uretimTubeBrcListInsertModalHide', event => {
            $('#uretimTubeBrcListInsertModal').modal('hide');
        });
        
    </script>

    @include('parts.alert')


</div>
