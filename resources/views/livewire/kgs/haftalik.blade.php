<div>
    <div class="py-3">
        <h3 class="">Haftalık Excel dosyası yüklerken dikkat edilmesi gereken hususlar : </h3>
        <ul>
            <li>Yüklenen kimlik bilgilerinde tekrarlanan "kgs_id" si bulunmamalı, aksi halde hata alırsınız.</li>
            <li>Örnek excel dosyalarındaki stun başlıklarını değiştirmeyiniz.</li>
            <li>07:00 - 09:00 arası yapılan girişler 8:30 kabul edilir.</li>
            <li>17:00 - 18:00 arası yapılan girişler 17:30 kabul edilir.</li>
            <li>Girişi olan ve çıkışı olamayan kişi bir daha giriş yaptığında 8:30 saat mesai hesaplanır.</li>
            <li>Girişi olan biri çıkış yapmaz ve çıkışı, bir sonraki girişinden önce ise mesai'nin fazla olarak hesaplanmasına yol açar. Böyle bir durumda verinin düzeltilmesi gerekir.</li>
        </ul>
        <a wire:click="exampleWeeklyShema()" class="btn btn-primary btn-xs mb-2">Örnek Haftalık KGS Excel'i İndir [ <i class="fas fa-file-excel"></i> Excel ]</a>
        <a wire:click="process(2,'insert')" class="btn btn-success btn-xs mb-2">Haftalık giriş & çıkış Yükle [ <i class="fas fa-file-excel"></i> Excel ]</a>
    </div>

    <div class="row">
        <div class="col">
            <section class="card ">
                <header class="card-header">
                    <div class="card-actions">
                        {{-- <a href="#" class="card-action card-action-toggle text-white" data-card-toggle></a> --}}
                        {{-- <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a> --}}
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <h2 class="card-title">Haftalık giriş & çıkış</h2>
                        </div>
                    </div>
                </header>
                <div class="card-body">
                    <a wire:click="calculate" class="btn btn-primary btn-md mb-2">Hesapla ve İndir</a>
                    <p class="d-inline-flex text-sm">Bu işlem birkaç dakika alabilir. Lütfen bekleyiniz.</p>
                </div>
            </section>
        </div>
    </div>
    <div class="modal fade" id="importKgsHaftalik" tabindex="-1" aria-labelledby="importKgsHaftalikLabel" aria-hidden="true" wire:ignore.self>
        <form autocomplete="off" wire:submit.prevent="importAndCalculateHaftalikPuantaj">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ trans('site.modal.header.'.$action) }}</h5>
                        <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">KGS Haftalık Giriş & Çıkış [Excel]</label>
                            <div class="input-append">
                                <span class="btn btn-default btn-file">
                                    <input type="file" wire:model.defer="kgsHaftalik.file" class="form-control @error('file') is-invalid @enderror" />
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
        window.addEventListener('importKgsHaftalikShow', event => {
            $('#importKgsHaftalik').modal('show');
        });
        window.addEventListener('importKgsHaftalikHide', event => {
            $('#importKgsHaftalik').modal('hide');
        });
    </script>
    @include('parts.alert')
</div>
