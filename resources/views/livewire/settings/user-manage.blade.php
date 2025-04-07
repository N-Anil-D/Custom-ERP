<div>
    <div class="row">
        <div class="col">
            <section class="card">
                <header class="card-header">
                    <div class="card-actions">
                        <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                        <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
                    </div>
                    <h2 class="card-title">Kullanıcı yönetimi</h2>
                    <p class="card-subtitle">Dikkat ! Bu sayfada yapacağınız işlemler programın genel akışını
                        etkilemektedir.</p>
                </header>

                <div class="card-body">
                    <a wire:click="process(0,'insert')" class="btn btn-primary btn-xs mb-2">{!! trans('site.button.insert') !!}</a>
                    <a wire:click="showTelegramMsg()" class="btn btn-primary btn-xs mb-2"><i class="fab fa-telegram"></i>
                        Telegram kayıtlarına gözat
                    </a>
                    <a wire:click="process({{ Auth::user()->id }},'sendMessage')" class="btn btn-primary btn-xs mb-2"><i class="far fa-comment"></i></i>
                        Bot ile mesaj gönder
                    </a>
                    <div class="col-lg-12 col-sm-12 pb-sm-3 pb-md-0 mb-2">
                        <input wire:model="search" type="search" class="form-control" placeholder="aramak istediğiniz kullanıcı adını ya da e-posta adresini buraya yazınız.">
                    </div>

                    <div class="table-responsive">
                        <table class="table table-responsive-md table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="center">Telegram test</th>
                                    <th>Kullanıcı adı</th>
                                    <th>Tel No</th>
                                    <th class="center">Günlük rapor alabilir ?</th>
                                    <th class="center">Sayımı onaylayabilir ?</th>
                                    <th class="center">Tema</th>
                                    <th colspan="3" class="center">İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($activeUsers as $row)
                                    <tr>
                                        <td style="{{ (!$row->active) ? 'color:red' : '' }}">{{ $row->id }}</td>
                                        <td class="center">
                                            <button wire:click="telegramTest({{ $row->id }})" class="btn {{ ($row->telegram_id) ? 'btn-primary' : 'btn-default' }} btn-xs"><i class="fab fa-telegram"></i></button>
                                        </td>
                                        <td style="{{ (!$row->active) ? 'color:red' : '' }}">
                                            {{ $row->name }}
                                        </td>
                                        <td style="{{ (!$row->active) ? 'color:red' : '' }}">{{ $row->tel_no }}</td>
                                        <td class="center">
                                            <input wire:click="authorityProcess({{ $row->id }}, 'production_report')" type="checkbox" {{ ($row->production_report) ? 'checked="checked"' : '' }}>                                            
                                        </td>
                                        <td class="center">
                                            <input wire:click="authorityProcess({{ $row->id }}, 'can_confirm_count')" type="checkbox" {{ ($row->can_confirm_count) ? 'checked="checked"' : '' }}>                                            
                                        </td>
                                        <td class="center">{!! ($row->theme) ? '<i class="bx bxs-moon"></i>' : '<i class="bx bx-sun"></i>' !!}</td>
                                        <td class="actions-hover actions-fade center">
                                            <a wire:click="process({{ $row->id }}, 'update')">
                                                <i class="fas fa-pencil-alt fa-lg"></i>
                                            </a>
                                        </td>
                                        <td class="actions-hover actions-fade center">
                                            <a wire:click="process({{ $row->id }}, 'delete')" class="delete-row">
                                                @if($row->active)
                                                    <i class="far fa-trash-alt fa-lg"></i>
                                                @else
                                                    <i class="far fa-check-circle fa-lg"></i>
                                                @endif
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
    
    <div class="row mt-5">
        <div class="col">
            <section class="card card-collapsed">
                <header class="card-header">
                    <div class="card-actions">
                        <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                        <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
                    </div>
                    <h2 class="card-title">İnaktif kullanıcılar</h2>
                    <p class="card-subtitle">Dikkat ! Bu sayfada yapacağınız işlemler programın genel akışını
                        etkilemektedir.</p>
                </header>

                <div class="card-body">
                    <div class="col-lg-12 col-sm-12 pb-sm-3 pb-md-0 mb-2">
                        <input wire:model="search" type="search" class="form-control" placeholder="aramak istediğiniz kullanıcı adını ya da e-posta adresini buraya yazınız.">
                    </div>
                    <div class="table-responsive">
                        <table class="table table-responsive-md table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Kullanıcı adı</th>
                                    <th>Tel No</th>
                                    <th class="center">Tema</th>
                                    <th colspan="3" class="center">İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inActiveUsers as $row)
                                    <tr>
                                        <td style="{{ (!$row->active) ? 'color:red' : '' }}">{{ $row->id }}</td>
                                        <td style="{{ (!$row->active) ? 'color:red' : '' }}">
                                            {{ $row->name }}
                                        </td>
                                        <td style="{{ (!$row->active) ? 'color:red' : '' }}">{{ $row->tel_no }}</td>
                                        <td class="center">{!! ($row->theme) ? '<i class="bx bxs-moon"></i>' : '<i class="bx bx-sun"></i>' !!}</td>
                                        <td class="actions-hover actions-fade center">
                                            <a wire:click="process({{ $row->id }}, 'update')">
                                                <i class="fas fa-pencil-alt fa-lg"></i>
                                            </a>
                                        </td>
                                        <td class="actions-hover actions-fade center">
                                            <a wire:click="process({{ $row->id }}, 'delete')" class="delete-row">
                                                @if($row->active)
                                                    <i class="far fa-trash-alt fa-lg"></i>
                                                @else
                                                    <i class="far fa-check-circle fa-lg"></i>
                                                @endif
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
    @if($user)
    <div class="modal fade" id="userDeleteModal" tabindex="-1" aria-labelledby="userDeleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        @if($user['active'])
                        Pasife alma işlemini onaylayınız
                        @else
                        Aktif etme işlemini onaylayınız
                        @endif
                    </h5>
                    <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    
                    <strong>Dikkat!
                        <br>
                        @if( $user['active'] )
                            Kullanıcı pasife alınacak ve sisteme girişi engellenecek!
                        @else
                            Kullanıcı aktif olacak ve sisteme giriş yapabilecek.
                        @endif
                    </strong>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('site.modal.button.cancel') }}</button>
                    <button wire:click="delete" type="button" class="btn btn-primary">{{ trans('site.modal.button.confirm') }}</button>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    {{-- modal insert or update --}}
    <div class="modal fade" id="userInsertOrUpdateModal" tabindex="-1" aria-labelledby="userInsertOrUpdateModalLabel"
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
                            <label class="form-label">Kullanıcı Adı & Soyadı</label>
                            <input wire:model.defer="user.name" type="text" class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="row">

                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">Telefon No.</label>
                                    <input id="tel_no" wire:model.defer="user.tel_no" type="tel_no" class="form-control @error('tel_no') is-invalid @enderror" placeholder="(555) 123 4567">
                                    @error('tel_no')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">Telegram ID</label>
                                    <input wire:model.defer="user.telegram_id" type="text" class="form-control @error('telegram_id') is-invalid @enderror">
                                    @error('telegram_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">E-posta</label>
                            <input wire:model.defer="user.email" type="text" class="form-control @error('email') is-invalid @enderror">
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Yetkilendirme</label>
                            <select wire:model.defer="user.authority" multiple data-plugin-selectTwo class="form-control populate" style="height:140px">
                                @foreach($sideBar as $side)
                                <option value="{{ $side->id }}">{{ $side->link }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Depo Yetkilendirme</label>
                            <select wire:model.defer="user.warehouses" multiple data-plugin-selectTwo class="form-control populate" style="height:140px">
                                @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">İzinler</label>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    @if ($user)
                                        <tr>
                                            <td>Günlük rapor alabilir ? {!! ($user['production_report']) ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times-circle text-secondary"></i>' !!}</td>
                                            <td>
                                                <input wire:model.defer="user.production_report" type="checkbox" {{ ($user['production_report']) ? 'checked="checked"' : '' }}>                                            
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Alım talebini onaylayabilir ? {!! ($user['buy_assent']) ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times-circle text-secondary"></i>' !!}</td>
                                            <td><input wire:model.defer="user.buy_assent" type="checkbox" {{ ($user['buy_assent']) ? 'checked="checked"' : '' }}></td>
                                        </tr>
                                        <tr>
                                            <td>Alım yapabilir ? {!! ($user['can_buy']) ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times-circle text-secondary"></i>' !!}</td>
                                            <td><input wire:model.defer="user.can_buy" type="checkbox" {{ ($user['can_buy']) ? 'checked="checked"' : '' }}></td>
                                        </tr>
                                        <tr>
                                            <td>Alımı onaylayabilir ? {!! ($user['confirm_buy']) ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times-circle text-secondary"></i>' !!}</td>
                                            <td><input wire:model.defer="user.confirm_buy" type="checkbox" {{ ($user['confirm_buy']) ? 'checked="checked"' : '' }}></td>
                                        </tr>
                                        <tr>
                                            <td>Çıkış yapabilir ? {!! ($user['can_exit']) ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times-circle text-secondary"></i>' !!}</td>
                                            <td><input wire:model.defer="user.can_exit" type="checkbox" {{ ($user['can_exit']) ? 'checked="checked"' : '' }}></td>
                                        </tr>
                                        <tr>
                                            <td>Çıkışı onaylayabilir ? {!! ($user['confirm_exit']) ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times-circle text-secondary"></i>' !!}</td>
                                            <td><input wire:model.defer="user.confirm_exit" type="checkbox" {{ ($user['confirm_exit']) ? 'checked="checked"' : '' }}></td>
                                        </tr>
                                        <tr>
                                            <td>Tüm depoları sayabilir ? {!! ($user['can_count_all']) ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times-circle text-secondary"></i>' !!}</td>
                                            <td><input wire:model.defer="user.can_count_all" type="checkbox" {{ ($user['can_count_all']) ? 'checked="checked"' : '' }}></td>
                                        </tr>
                                        <tr>
                                            <td>Sayımı onaylayabilir ? {!! ($user['can_confirm_count']) ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times-circle text-secondary"></i>' !!}</td>
                                            <td><input wire:model.defer="user.can_confirm_count" type="checkbox" {{ ($user['can_confirm_count']) ? 'checked="checked"' : '' }}></td>
                                        </tr>
                                        <tr>
                                            <td>Üretim raporu isteyebilir ? {!! ($user['can_request_report']) ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times-circle text-secondary"></i>' !!}</td>
                                            <td><input wire:model.defer="user.can_request_report" type="checkbox" {{ ($user['can_request_report']) ? 'checked="checked"' : '' }}></td>
                                        </tr>
                                        <tr>
                                            <td>Kalite kontrol ? {!! ($user['quality_control']) ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times-circle text-secondary"></i>' !!}</td>
                                            <td><input wire:model.defer="user.quality_control" type="checkbox" {{ ($user['quality_control']) ? 'checked="checked"' : '' }}></td>
                                        </tr>
                                        <tr>
                                            <td>Kalite kontrol müdürü? {!! ($user['confirm_quality_control']) ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times-circle text-secondary"></i>' !!}</td>
                                            <td><input wire:model.defer="user.confirm_quality_control" type="checkbox" {{ ($user['confirm_quality_control']) ? 'checked="checked"' : '' }}></td>
                                        </tr>
                                        <tr>
                                            <td>İş emri seviyesi ? {!! $user['work_order_level'] >= 1 ? '<i class="fas fa-check text-success"></i>' : '<i class="fas fa-times-circle text-secondary"></i>' !!} <br>[0:Erişemez|1:Erişebilir|2:Düzenleyebilir]</td>
                                            <td>
                                                <select wire:model.defer="user.work_order_level" class="form-control ">
                                                    <option value="0"{{ $user['work_order_level'] == 0 ? 'selected' : '' }}>0</option>
                                                    <option value="1"{{ $user['work_order_level'] == 1 ? 'selected' : '' }}>1</option>
                                                    <option value="2"{{ $user['work_order_level'] == 2 ? 'selected' : '' }}>2</option>
                                                </select>
                                            </td>
                                        </tr>
                                    @endif
                                </table>
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
    
    

    <div class="modal fade" id="sendMessageByBotModal" tabindex="-1" aria-labelledby="sendMessageByBotModalLabel"
        aria-hidden="true">
        <form autocomplete="off" wire:submit.prevent="sendMessage">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">#InvamedBot ile mesaj gönder</h5>
                        <button type="button" class="btn-close bg-dark" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Mesaj Gönderilecek Kullanıcıları Seçiniz</label>
                            <select wire:model.defer="sendMessageList" multiple data-plugin-selectTwo class="form-control populate" style="height:180px">
                                @foreach($activeUserList as $sendMessageListKey => $sendMessageListValue)
                                <option value="{{ $sendMessageListKey }}">{{ $sendMessageListValue }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group row pb-3">
                            <label class="control-label pt-2" for="textareaDefault">Mesaj</label>
                            <div class="col mt-1">
                                <textarea wire:model.defer='botMessage.message' class="form-control" rows="4"></textarea>
                            </div>
                        </div>
    
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ trans('site.modal.button.cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ trans('site.modal.button.send') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    

    
    
    <script src="{{ url('login-file/imask.js') }}"></script>
    
    <script>
        window.addEventListener('userDeleteModalShow', event => {
            $('#userDeleteModal').modal('show');
        });
        window.addEventListener('userDeleteModalHide', event => {
            $('#userDeleteModal').modal('hide');
        });
        window.addEventListener('userInsertOrUpdateModalShow', event => {
            $('#userInsertOrUpdateModal').modal('show');
        });
        window.addEventListener('userInsertOrUpdateModalHide', event => {
            $('#userInsertOrUpdateModal').modal('hide');
        });
        window.addEventListener('sendMessageByBotModalShow', event => {
            $('#sendMessageByBotModal').modal('show');
        });
        window.addEventListener('sendMessageByBotModalHide', event => {
            $('#sendMessageByBotModal').modal('hide');
        });

        var maskElement = document.getElementById('tel_no');
        var maskOptions = {
            mask: '(000) 000 0000'
        };
        var mask = IMask(maskElement, maskOptions);

    </script>

    @include('parts.alert')
    @include('parts.alertify')
    
    
</div>
