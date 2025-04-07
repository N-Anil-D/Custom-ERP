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
                    <h2 class="card-title">Kullanıcı Logları</h2>
                    <p class="card-subtitle"></p>
                    <p class="card-subtitle">Toplam {{ $data->total() }} kayıttan {{ $data->count() }} adet listeleniyor.</p>
                </header>

                <div class="card-body" wire:poll.3s>
                    <div class="table-responsive">
                        <table class="table table-responsive-md table-hover table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Url</th>
                                    <th>User</th>
                                    <th>IP</th>
                                    <th>Device</th>
                                    <th>Browser</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $row)
                                <tr>
                                    <td>{{ $row->id }}</td>
                                    <td>{{ $row->created_at }}</td>
                                    <td>{{ $row->uri }}</td>
                                    <td>{{ ($row['logToUsr']) ? $row['logToUsr']['name'] : 'Guest' }}</td>
                                    {{-- <td>{{ ($row->LogToUsr) ? $row->logToUsr->name : 'Guest' }}</td> --}}
                                    <td>{{ $row->ip }}</td>
                                    <td>{!! $row->deviceIcon() !!} ver: {{ $row->deviceVer }}</td>
                                    <td>{!! $row->browserIcon() !!} ver: ({{ $row->browserVer }})</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    <hr>
                    {{ $data->links() }}
                    </div>
                </div>

            </section>
        </div>
    </div>
    
</div>
