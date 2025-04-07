<div>
    <div class="row">
        <div class="col">
            <section class="card">
                <header class="card-header">
                    <div class="card-actions">
                        <a href="#" class="card-action card-action-toggle" data-card-toggle></a>
                        <a href="#" class="card-action card-action-dismiss" data-card-dismiss></a>
                    </div>
                    <h2 class="card-title">Kullanıcıların Son işlem tarihi</h2>
                </header>

                <div class="card-body">
                    {{-- <div class="col-lg-12 col-sm-12 pb-sm-3 pb-md-0 mb-2">
                        <input wire:model="search" type="search" class="form-control" placeholder="aramak istediğiniz kullanıcı adını ya da e-posta adresini buraya yazınız.">
                    </div> --}}

                    <div class="table-responsive">
                        <table class="table table-responsive-md table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>USER ID</th>
                                    <th>Kullanıcı Adı</th>
                                    <th>Tel No</th>
                                    <th>Toplam İşlem Sayısı</th>
                                    <th>Son İşlem Tarihi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($datas as $row)
                                    @if ($row->findUser->active)
                                        <tr class="font-weight-bold">
                                            <td>{{ $row->findUser->id }}</td>
                                            <td>{{ $row->findUser->name }}</td>
                                            <td>{{ $row->findUser->tel_no }}</td>
                                            <td>{{ $row->action }}</td>
                                            <td class="{{ (\Carbon\Carbon::now()->diffInHours(\Carbon\Carbon::parse($row->updated_at))) <= 24 ? 'text-success' : 'text-danger' }}">{{ $row->updated_at }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </div>

    {{-- @include('parts.alert') --}}
    @include('parts.alertify')
    
    
</div>
