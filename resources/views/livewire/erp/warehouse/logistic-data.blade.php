<div>
    <div class="row mt-3">
        <div class="col">
            <section class="card">
                <header class="card-header">
                    <p class="card-subtitle">Toplam {{ $logisticDatas->total() }} kayıttan {{ $logisticDatas->count() }} adet listeleniyor.</p>
                </header>
                <div class="card-body">
                    <div class="col-lg-12 col-sm-12 pb-sm-3 pb-md-0 mb-2">
                        <input wire:model="search" type="search" class="form-control" placeholder="Aramak istediğiniz ürünün adını ya da diğer bilgilerini buraya yazınız.">
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#ID</th>
                                    <th>Ürün Adı</th>
                                    <th>Raf</th>
                                    <th>Türü</th>
                                    <th>Son Değişiklik</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logisticDatas as $logisticData)
                                    @if (!is_null($logisticData))
                                        <tr>
                                            @if (isset($selectedItemID) && ($selectedItemID == $logisticData->id))
                                                <td class="d-flex justify-content-center">
                                                    <button wire:click="asignLocation()" class="btn btn-primary btn-xs">{{ trans('site.modal.button.save') }}</button>
                                                </td>
                                                <td><div class="row justify-content-between"><div class="col-10">{{ $logisticData->name }}</div><div class="col-2 text-end">{{ $logisticData?->stock($warehouseID)->amount .' ('. $logisticData->itemToUnit->code.')' }}</div></div></td>
                                                <td colspan="3">
                                                    @if ($warehouseID == 1)
                                                        <div class="row justify-content-between align-items-center">
                                                            <div class="col-lg-3">
                                                                <select class="form-control" data-plugin-multiselect data-plugin-options='{ "maxHeight": 200 }' wire:model.change="p1">
                                                                    <option value="">-</option>
                                                                    <option value="HM">HM</option>
                                                                    <option value="MM">MM</option>
                                                                    <option value="AMB">AMB</option>
                                                                    <option value="ORTHM">ORTHM</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <select class="form-control" data-plugin-multiselect data-plugin-options='{ "maxHeight": 200 }' wire:model.change="p2">
                                                                    @if (isset($p1))
                                                                        <option value="" selected>-</option>
                                                                        @if ($p1 == "HM")
                                                                            @for ($i = 1; $i <= 66; $i++)
                                                                                <option value="{{ $i }}">{{ $i }}</option>
                                                                            @endfor
                                                                        @elseif ($p1 == "MM")
                                                                            <option value="I">İ</option>
                                                                            <option value="K">K</option>
                                                                            <option value="L">L</option>
                                                                            <option value="M">M</option>
                                                                        @elseif ($p1 == "AMB")
                                                                            @for ($i = 1; $i <= 21; $i++)
                                                                                <option value="{{ $i }}">{{ $i }}</option>
                                                                            @endfor
                                                                        @elseif ($p1 == "ORTHM")
                                                                            @for ($i = 1; $i <= 20; $i++)
                                                                                <option value="{{ $i }}">{{ $i }}</option>
                                                                            @endfor
                                                                        @endif
                                                                    @else
                                                                        <option value="">-</option>
                                                                    @endif
                                                                </select>
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <select class="form-control" data-plugin-multiselect data-plugin-options='{ "maxHeight": 200 }' wire:model.change="p3">
                                                                    @if (isset($p2))
                                                                        <option value="" selected>-</option>
                                                                        @if ($p2 == "i")
                                                                            @for ($i = 1; $i <= 24; $i++)
                                                                                <option value="{{ $i }}">{{ $i }}</option>
                                                                            @endfor
                                                                        @elseif ($p2 == "k" || $p2 == "l")
                                                                            @for ($i = 1; $i <= 18; $i++)
                                                                                <option value="{{ $i }}">{{ $i }}</option>
                                                                            @endfor
                                                                        @elseif ($p2 == "m")
                                                                            @for ($i = 1; $i <= 51; $i++)
                                                                                <option value="{{ $i }}">{{ $i }}</option>
                                                                            @endfor
                                                                        @else
                                                                            <option value="1">1</option>
                                                                            <option value="2">2</option>
                                                                            <option value="3">3</option>
                                                                            @endif
                                                                        @else
                                                                            <option value="">-</option>
                                                                            <option value="TUM RAF">TUM RAF</option>
                                                                            <option value="PALET">PALET</option>
                                                                            <option value="OFIS">OFIS</option>
                                                                            <option value="KİMYASAL ODA">KİMYASAL ODA</option>
                                                                        @endif
                                                                </select>
                                                            </div>
                                                        </div>
                                                    @elseif ($warehouseID == 27)
                                                        <div class="row justify-content-between align-items-center">
                                                            <div class="col-lg-3">
                                                                <select class="form-control" data-plugin-multiselect data-plugin-options='{ "maxHeight": 200 }' wire:model.change="p1">
                                                                    <option value="">-</option>
                                                                    <option value="A">A</option>
                                                                    <option value="B">B</option>
                                                                    <option value="C">C</option>
                                                                    <option value="D">D</option>
                                                                    <option value="E">E</option>
                                                                </select>
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <select class="form-control" data-plugin-multiselect data-plugin-options='{ "maxHeight": 200 }' wire:model.change="p2">
                                                                    @if (isset($p1))
                                                                        <option value="" selected>-</option>
                                                                        @if ($p1 == "A" ||$p1 == "C")
                                                                            @for ($i = 1; $i <= 9; $i++)
                                                                                <option value="{{ $i }}">{{ $i }}</option>
                                                                            @endfor
                                                                        @elseif ($p1 == "B" || $p1 == "D")
                                                                            @for ($i = 1; $i <= 6; $i++)
                                                                                <option value="{{ $i }}">{{ $i }}</option>
                                                                            @endfor
                                                                        @elseif ($p1 == "E")
                                                                            @for ($i = 1; $i <= 7; $i++)
                                                                                <option value="{{ $i }}">{{ $i }}</option>
                                                                            @endfor
                                                                        @endif
                                                                    @else
                                                                        <option value="">-</option>
                                                                    @endif
                                                                </select>
                                                            </div>
                                                            <div class="col-lg-3">
                                                                <select class="form-control" data-plugin-multiselect data-plugin-options='{ "maxHeight": 200 }' wire:model.change="p3">
                                                                    @if (isset($p2))
                                                                        <option value="" selected>-</option>
                                                                        @for ($i = 1; $i <= 4; $i++)
                                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                                        @endfor
                                                                    @else
                                                                        <option value="" selected>-</option>
                                                                    @endif
                                                                </select>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </td>
                                            @else
                                                <td>
                                                    <div class="btn-group flex-wrap">
                                                        <a style="text-decoration: none;" href="#" class="dropdown-toggle" data-bs-toggle="dropdown">{{ $logisticData->id }}<span class="caret"></span></a>
                                                        
                                                            <div class="dropdown-menu" role="menu">
                                                            <button wire:click="asignNewLocationData({{ $logisticData->id }})" class="dropdown-item text-1">
                                                                <i class="fas fa-map-marker-alt"></i>
                                                                Raf ata
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-row justify-content-between">
                                                        <div>
                                                            {{ $logisticData->name }}
                                                        </div>
                                                        <div>
                                                            {{ $logisticData?->stock($warehouseID)->amount .' ('. $logisticData->itemToUnit->code.')' }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        @foreach ($logisticData->location($logisticData->id,$warehouseID) as $location)
                                                        <div class="d-flex flex-row justify-content-between {{ count($logisticData->location($logisticData->id,$warehouseID)) ? 'mb-2':'' }}">
                                                            <div>
                                                                {{ $location?->p1 }}
                                                                {{ $location->p1 ? ' - '.$location?->p2 : $location?->p2  }}
                                                                {{ $location->p2 ? ' - '.$location?->p3 : $location?->p3  }}
                                                            </div>  
                                                            @if (isset($location->p1) || isset($location->p2) || isset($location->p3))
                                                                <div>
                                                                    <a class="btn btn-xs btn-warning my-lg-0 my-1" wire:click="editExistingLocation({{ $location->id.','.$logisticData->id }})"><i class="far fa-edit"></i></a>
                                                                    <a class="btn btn-xs btn-danger my-lg-0 my-1" wire:click="deleteLocation({{ $location->id }})"><i class="far fa-times-circle"></i></a>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </td>
                                                <td>{{ $logisticData->getType() }}</td>
                                                <td>{{ $logisticData->updated_at }}</td>
                                            @endif
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $logisticDatas->links() }}
                </div>

            </section>
        </div>
    </div>

    
    <script>

    </script>

    @include('parts.alert')
    {{-- @include('parts.alertify') --}}

</div>
