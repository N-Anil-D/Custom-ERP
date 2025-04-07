

    @php
        $notifyCount = 0;
        $notifyCount = $user->pendingApprovals->count() + $requested->count() + $reConfirm->count();
        
        if ($user->confirm_buy) {
            $notifyCount += $purchaseConfirmation->count();
        }

        if($user->buy_assent) {
            $notifyCount += $wtbRequestsFromMe->count();
        }                 

        if($user->can_buy) {
            $notifyCount += $validatedPurchase->count();
        }                 

        if($user->confirm_exit) {
            $notifyCount += $saleConfirmation->count();
        }                 

        if($user->confirm_quality_control) {
            $notifyCount += $packageConfirm->count();
        }                 

    @endphp


    <li>
        <a href="#" class="dropdown-toggle notification-icon" data-bs-toggle="dropdown">
            <i class="bx bx-bell"></i>
            <span class="badge">{{ $notifyCount > 0 ? $notifyCount : '' }}</span>
        </a>

        {{-- @if ($notifyCount > 0) --}}
            <div class="dropdown-menu notification-menu x-large">
                <div class="notification-title">
                    <span class="float-end badge badge-default">{{ $notifyCount }}</span>
                    Bildirimlerim (Onay vereceklerim)
                </div>

                <div class="content">
                    <ul>
                        {{-- ürün alımı --}}
                        @if ($user->confirm_buy)
                            @foreach ($purchaseConfirmation as $purchase)
                                @if (isset($purchase->getItem))
                                    <li>
                                        <a href="{{ route('myNotify') }}" class="clearfix">
                                            <div class="image">
                                                <i class="fas fa-truck-loading bg-success text-light"></i>
                                            </div>
                                            
                                            <span class="message">{{ $purchase->getType() }}</span>
                                            
                                            <span class="message">
                                                {{ $purchase->getItem->name }} miktar : {{ $purchase->amount }} {{ $purchase->getItem->itemToUnit->content }}
                                            </span>
                                            
                                            <span class="message mx-5">
                                                <span class="badge badge-danger"><i class="fas fa-truck-loading fa-lg"></i></span>
                                                <i class="fas fa-angle-double-right"></i>
                                                <span class="badge badge-success">{{ $purchase->getIncreasedWarehouse->name }}</span>
                                            </span>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                        {{-- onaylanmış ürün alımı --}}
                        @if ($user->can_buy)
                            @foreach ($validatedPurchase as $purchase)
                                @if (isset($purchase->getItem))
                                    <li>
                                        <a href="{{ route('myNotify') }}" class="clearfix">
                                            <div class="image">
                                                <i class="fas fa-truck-loading bg-success text-light"></i>
                                            </div>

                                            <b><span class="message">{{ $purchase->getType() }}</span></b>
                                            <span class="message">Onaylayan : {{ $purchase->getSender->name }}</span>

                                            <span class="message">
                                                Ürün : {{ $purchase->getItem->name }} miktar : {{ $purchase->amount }} {{ $purchase->getItem->itemToUnit->content }}
                                            </span>

                                            <span class="message mx-5">
                                                <span class="badge badge-danger"><i class="fas fa-truck-loading fa-lg"></i></span>
                                                <i class="fas fa-angle-double-right"></i>
                                                <span class="badge badge-success">{{ $purchase->getIncreasedWarehouse->name }}</span>
                                            </span>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                        {{-- ürün alım talebi --}}
                        @if ($wtbRequestsFromMe->count())
                            @foreach ($wtbRequestsFromMe as $wtbRequest)
                                @if (isset($wtbRequest->getItem))
                                    <li>
                                        <a href="{{ route('myNotify') }}" class="clearfix">
                                            <div class="image">
                                                <i class="fas fa-truck-loading bg-success text-light"></i>
                                            </div>

                                            <span class="message">{{ $wtbRequest->getType() }}</span>

                                            <span class="message">
                                                {{ $wtbRequest->getItem->name }} miktar : {{ $wtbRequest->amount }} {{ $wtbRequest->getItem->itemToUnit->content }}
                                            </span>

                                            <span class="message mx-5">
                                                <span class="badge badge-danger"><i class="fas fa-truck-loading fa-lg"></i></span>
                                                <i class="fas fa-angle-double-right"></i>
                                                <span class="badge badge-success">{{ $wtbRequest->getIncreasedWarehouse->name }}</span>
                                            </span>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        @endif

                        {{-- ürün satışı --}}
                        @if ($user->confirm_exit)
                            @foreach ($saleConfirmation as $sale)
                                @if (isset($sale->getItem))
                                    <li>
                                        <a href="{{ route('myNotify') }}" class="clearfix">

                                            <div class="image">
                                                <i class="fas fa-dolly-flatbed bg-info text-light"></i>
                                            </div>

                                            <span class="message">{{ $sale->getType() }}</span>
                                            <span class="message">
                                                {{ $sale->getItem->name }} miktar : {{ $sale->amount }} {{ $sale->getItem->itemToUnit->content }}
                                            </span>

                                            <span class="message mx-5">
                                                <span class="badge badge-danger">{{ $sale->getDwindlingWarehouse->name }}</span>
                                                <i class="fas fa-angle-double-right"></i>
                                                <span class="badge badge-success"><i class="fas fa-dolly-flatbed fa-lg"></i></span>
                                            </span>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        @endif

                        {{-- paketleme onayı --}}
                        @if ($user->confirm_quality_control)
                            @foreach ($packageConfirm as $package)
                                @if (isset($package->item))
                                    <li>
                                        <a href="{{ route('myNotify') }}" class="clearfix">

                                            <div class="image">
                                                <i class="fas fa-dolly-flatbed bg-info text-light"></i>
                                            </div>

                                            <span class="message">{{ $package->getGeneralStatus() }}</span>
                                            <span class="message">
                                                {{ $package->item->name }} miktar : {{ $package->amount }} {{ $package->item->itemToUnit->content }}
                                            </span>

                                            <span class="message mx-5">
                                                <span class="badge badge-success"><i class="fas fa-boxes"></i></span>
                                                
                                            </span>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        @endif

                        {{-- depolar arası transfer // gönderici talep sahibi --}}
                        @foreach ($user->pendingApprovals as $transfer)
                            @if (isset($transfer->getItem))
                                <li>
                                    <a href="{{ route('myNotify') }}" class="clearfix">
                                        <div class="image">
                                            <i class="fas fa-exchange-alt bg-primary text-light"></i>
                                        </div>

                                        <span class="message">{{ $transfer->getType() }}</span>

                                        <span class="message">
                                            {{ $transfer->getItem?->name }} miktar : {{ $transfer->erp_approvals_amount }} {{ $transfer->getItem?->itemToUnit?->content }}
                                        </span>

                                        <span class="message mx-5">
                                            <span class="badge badge-danger">{{ $transfer->getDwindlingWarehouse->name }}</span>
                                            <i class="fas fa-angle-double-right"></i>
                                            <span class="badge badge-success">{{ $transfer->getIncreasedWarehouse->name }}</span>
                                        </span>
                                    </a>
                                </li>
                            @endif
                        @endforeach

                        {{-- depolar arası transfer // alıcı talep sahibi --}}
                        @foreach ($requested as $req)
                            @if (isset($req->getItem))
                                <li>
                                    <a href="{{ route('myNotify') }}" class="clearfix">
                                        <div class="image">
                                            <i class="fas fa-exchange-alt bg-primary text-light"></i>
                                        </div>

                                        <span class="message">{{ $req->getType() }}</span>

                                        <span class="message">
                                            {{ $req->getItem->name }} miktar : {{ $req->amount }} {{ $req->getItem->itemToUnit->content }}
                                        </span>

                                        <span class="message mx-5">
                                            <span class="badge badge-danger">{{ $req->getDwindlingWarehouse->name }}</span>
                                            <i class="fas fa-angle-double-right"></i>
                                            <span class="badge badge-success">{{ $req->getIncreasedWarehouse->name }}</span>
                                        </span>

                                    </a>
                                </li>
                            @endif
                        @endforeach

                        {{-- depolar arası transfer // alıcı talep sahibinin talebi onaylandı ve eline ulaştı onayı --}}
                        @foreach ($reConfirm as $req)
                            @if (isset($req->getItem))
                                <li>
                                    <a href="{{ route('myNotify') }}" class="clearfix">
                                        <div class="image">
                                            <i class="fas fa-exchange-alt bg-primary text-light"></i>
                                        </div>

                                        <span class="message">{{ $req->getType() }}</span>

                                        <span class="message">
                                            {{ $req->getItem->name }} miktar : {{ $req->amount }} {{ $req->getItem->itemToUnit->content }}
                                        </span>

                                        <span class="message mx-5">
                                            <span class="badge badge-danger">{{ $req->getDwindlingWarehouse->name }}</span>
                                            <i class="fas fa-angle-double-right"></i>
                                            <span class="badge badge-success">{{ $req->getIncreasedWarehouse->name }}</span>
                                        </span>

                                    </a>
                                </li>
                            @endif
                        @endforeach

                    </ul>

                    <hr />

                    <div class="text-end">
                        <a href="{{ route('myNotify') }}" class="view-more">Tümünü göster</a>
                    </div>
                </div>
            </div>
        {{-- @endif --}}
    </li>


