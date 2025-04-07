<div>
    <div class="row">
        <div class="col">
            <section class="card form-wizard" id="w2">
                <div class="tabs">
                    <ul class="nav nav-tabs nav-justify wizard-steps wizard-steps-style-2">
                        <li class="nav-item active">
                            <a href="#personal" data-bs-toggle="tab" class="nav-link text-center">
                                <span class="badge badge-primary"><i class="fas fa-user"></i></span>
                                Ürettiklerim
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#item" data-bs-toggle="tab" class="nav-link text-center">
                                <span class="badge badge-primary"><i class="fas fa-stethoscope"></i></span>
                                Ürüne Bağlı Üretim Raporu
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#warehouse" data-bs-toggle="tab" class="nav-link text-center">
                                <span class="badge badge-primary"><i class="fas fa-inbox"></i></span>
                                Odaya Bağlı Üretim Raporu
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#all" data-bs-toggle="tab" class="nav-link text-center">
                                <span class="badge badge-primary"><i class="far fa-calendar-check"></i></span>
                                Tarihe Göre Fabrika Üretim Raporu
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#transfer" data-bs-toggle="tab" class="nav-link text-center">
                                <span class="badge badge-primary"><i class="fas fa-exchange-alt"></i></span>
                                Transfer Raporu
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="personal" class="tab-pane p-3 active">
                            <form class="form-horizontal" novalidate="novalidate" autocomplete="off" action="{{ route('production.personal.report.download') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="personal">
                                <div class="form-group row pb-3">
                                    <div class="col">
                                        <ol class="{{ Auth::user()->theme ? 'text-warning my-3':'' }}">
                                            <li class="my-3">Bu pencereden alacağınız raporlar <b><u>kendinizin</u></b> açıp tamamladığı üretim raporunu verir.</li>
                                            <li class="my-3">Tarih aralığı seçip "{{ trans('site.button.takeRepport') }}" butonuna bastıktan sonra sistem size seçili tarihler arasındaki kişisel raporunuzu indirecektir.</li>
                                            <li class="my-3">Raporlarınız Excel olarak indirilecektir. [ CTRL + J ] yaparak tarayıcınızın indirilenlerine ulaşabilirsiniz.</li>
                                        </ol>
                                        <div class="input-daterange input-group my-5" data-plugin-datepicker>
                                        <label class="col-12 control-label py-2">Tarih Aralığı Seçiniz</label>
                                            <span class="input-group-text">
                                                <i class="fas fa-calendar-alt"></i>
                                            </span>
                                            <input type="text" class="form-control @error('start') is-invalid @enderror" name="start">

                                            <span class="input-group-text border-start-0 border-end-0 rounded-0">
                                                <i class="fas fa-arrows-alt-h"></i>
                                            </span>
                                            <input type="text" class="form-control @error('end') is-invalid @enderror" name="end">
                                        </div>
                                        <div class="my-4">
                                            <button type="submit" class="mb-1 mt-1 me-1 btn btn-primary">{{ trans('site.button.takeRepport') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div id="item" class="tab-pane p-3">
                            <form class="form-horizontal" novalidate="novalidate" autocomplete="off" action="{{ route('production.report.download') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="item">
                                <div class="form-group row pb-3">
                                    <div class="col">
                                        <ol class="{{ Auth::user()->theme ? 'text-warning my-3':'' }}">
                                            <li class="my-3">Bu pencereden alacağınız raporlar <b><u>seçili ürünün</u></b> üretim raporunu verir.</li>
                                            <li class="my-3">Tarih aralığı seçip "{{ trans('site.button.takeRepport') }}" butonuna bastıktan sonra sistem size seçili tarihler arasındaki ürün raporunu indirecektir.</li>
                                            <li class="my-3">Raporlarınız Excel olarak indirilecektir. [ CTRL + J ] yaparak tarayıcınızın indirilenlerine ulaşabilirsiniz.</li>
                                        </ol>
                                        <div class="form-group row">
                                            <div class="col">
                                                <select name="itemId" data-plugin-selectTwo class="form-control populate" data-plugin-options='{ "minimumInputLength": 3 }'>
                                                    <option>Lütfen ürün kodunu ya da adını giriniz...</option>
                                                    @foreach ($craftableItems as $item)
                                                        <option value="{{ $item->id }}">{{ $item->code }} - {{ $item->name }} - (birim : {{ $item->itemToUnit->content }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="input-daterange input-group my-5" data-plugin-datepicker>
                                            <label class="col-12 control-label py-2">Tarih Aralığı Seçiniz</label>
                                            <span class="input-group-text">
                                                <i class="fas fa-calendar-alt"></i>
                                            </span>
                                            <input type="text" class="form-control @error('start') is-invalid @enderror" name="start">
                                            <span class="input-group-text border-start-0 border-end-0 rounded-0">
                                                <i class="fas fa-arrows-alt-h"></i>
                                            </span>
                                            <input type="text" class="form-control @error('end') is-invalid @enderror" name="end">
                                    </div>
                                        <div class="my-4">
                                            <button type="submit" class="mb-1 mt-1 me-1 btn btn-primary">{{ trans('site.button.takeRepport') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div id="warehouse" class="tab-pane p-3">
                            <form class="form-horizontal" novalidate="novalidate" autocomplete="off" action="{{ route('production.report.download') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="warehouse">
                                <div class="form-group row pb-3">
                                    <div class="col">
                                        <ol class="{{ Auth::user()->theme ? 'text-warning my-3':'' }}">
                                            <li class="my-3">Bu pencereden alacağınız raporlar <b><u>seçili odanın || deponun</u></b> üretim raporunu verir.</li>
                                            <li class="my-3">Tarih aralığı seçip "{{ trans('site.button.takeRepport') }}" butonuna bastıktan sonra sistem size seçili tarihler arasındaki odanın & deponun raporunu indirecektir.</li>
                                            <li class="my-3">Raporlarınız Excel olarak indirilecektir. [ CTRL + J ] yaparak tarayıcınızın indirilenlerine ulaşabilirsiniz.</li>
                                        </ol>
                                        <div>
                                            <select name="warehouseId" data-plugin-selectTwo class="form-control populate">
                                                <option>Lütfen odanın || deponun kodunu ya da adını giriniz...</option>
                                                @foreach ($warehouses as $warehouse)
                                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="input-daterange input-group my-5" data-plugin-datepicker>
                                            <label class="col-12 control-label py-2">Tarih Aralığı Seçiniz</label>
                                            <span class="input-group-text">
                                                <i class="fas fa-calendar-alt"></i>
                                            </span>
                                            <input type="text" class="form-control @error('start') is-invalid @enderror" name="start">

                                            <span class="input-group-text border-start-0 border-end-0 rounded-0">
                                                <i class="fas fa-arrows-alt-h"></i>
                                            </span>
                                            <input type="text" class="form-control @error('end') is-invalid @enderror" name="end">

                                        </div>
                                        <div class="my-4">
                                            <button type="submit" class="mb-1 mt-1 me-1 btn btn-primary">{{ trans('site.button.takeRepport') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div id="all" class="tab-pane p-3">
                            <form class="form-horizontal" novalidate="novalidate" autocomplete="off" action="{{ route('production.report.download') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="all">
                                <div class="form-group row pb-3">
                                    <div class="col">
                                        <ol class="{{ Auth::user()->theme ? 'text-warning my-3':'' }}">
                                            <li class="my-3">Bu pencereden alacağınız raporlar <b><u>tüm fabrikanın</u></b> üretim raporunu verir.</li>
                                            <li class="my-3">Tarih aralığı seçip "{{ trans('site.button.takeRepport') }}" butonuna bastıktan sonra sistem size seçili tarihler arasındaki odanın & deponun raporunu indirecektir.</li>
                                            <li class="my-3">Raporlarınız Excel olarak indirilecektir. [ CTRL + J ] yaparak tarayıcınızın indirilenlerine ulaşabilirsiniz.</li>
                                        </ol>
                                        <div class="input-daterange input-group my-5" data-plugin-datepicker>
                                            <label class="col-12 control-label py-2">Tarih Aralığı Seçiniz</label>
                                            <span class="input-group-text">
                                                <i class="fas fa-calendar-alt"></i>
                                            </span>
                                            <input type="text" class="form-control @error('start') is-invalid @enderror" name="start">

                                            <span class="input-group-text border-start-0 border-end-0 rounded-0">
                                                <i class="fas fa-arrows-alt-h"></i>
                                            </span>
                                            <input type="text" class="form-control @error('end') is-invalid @enderror" name="end">

                                        </div>
                                        <div class="my-4">
                                            <button type="submit" class="mb-1 mt-1 me-1 btn btn-primary">{{ trans('site.button.takeRepport') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div id="transfer" class="tab-pane p-3">
                            <form class="form-horizontal" novalidate="novalidate" autocomplete="off" action="{{ route('production.report.download') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="transfer">
                                <div class="form-group row pb-3">
                                    <div class="col">
                                        <ol class="{{ Auth::user()->theme ? 'text-warning my-3':'' }}">
                                            <li class="my-3">Bu pencereden alacağınız raporlar <b><u>seçili odanın || deponun</u></b> transfer raporunu verir.</li>
                                            <li class="my-3">Tarih aralığı seçip "{{ trans('site.button.takeRepport') }}" butonuna bastıktan sonra sistem size seçili tarihler arasındaki odanın & deponun raporunu indirecektir.</li>
                                            <li class="my-3">Raporlarınız Excel olarak indirilecektir. [ CTRL + J ] yaparak tarayıcınızın indirilenlerine ulaşabilirsiniz.</li>
                                        </ol>
                                        <div class="mb-3">
                                            <select name="warehouseId" data-plugin-selectTwo class="form-control populate">
                                                <option>Lütfen odanın || deponun kodunu ya da adını giriniz...</option>
                                                <option value="0">Tüm Depolar</option>
                                                @foreach ($warehouses as $warehouse)
                                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <select name="incdecb" data-plugin-selectTwo class="form-control populate">
                                                <option value="1" selected>Sadece Çıkan Malzemeler</option>
                                                <option value="2">Sadece Giren Malzemeler</option>
                                                <option value="3">Giren ve Çıkan Malzemeler</option>
                                            </select>
                                        </div>
                                        <div class="input-daterange input-group my-5" data-plugin-datepicker>
                                            <label class="col-12 control-label py-2">Tarih Aralığı Seçiniz</label>
                                            <span class="input-group-text">
                                                <i class="fas fa-calendar-alt"></i>
                                            </span>
                                            <input type="text" class="form-control @error('start') is-invalid @enderror" name="start">

                                            <span class="input-group-text border-start-0 border-end-0 rounded-0">
                                                <i class="fas fa-arrows-alt-h"></i>
                                            </span>
                                            <input type="text" class="form-control @error('end') is-invalid @enderror" name="end">

                                        </div>
                                        <div class="my-4">
                                            <button type="submit" class="mb-1 mt-1 me-1 btn btn-primary">{{ trans('site.button.takeRepport') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    {{-- middleware'den redirect yiyince - parts.alert - çalışmıyor --}}
    {{-- @include('parts.alert') --}}
    {{-- middleware'den redirect yiyince - parts.alert - çalışmıyor --}}
    @include('parts.alertify')

</div>
