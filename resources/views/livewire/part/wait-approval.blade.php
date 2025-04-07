<li>
    <a href="#" class="dropdown-toggle notification-icon" data-bs-toggle="dropdown">
        <i class="bx bx-list-ol"></i>
        <span class="badge">{{ (Auth::user()->waitForApproval->count() > 0) ? Auth::user()->waitForApproval->count() : '' }}</span>
    </a>

    <div class="dropdown-menu notification-menu x-large">
        <div class="notification-title">
            <span class="float-end badge badge-default">{{ Auth::user()->waitForApproval->count() }}</span>
            Taleplerim (Onay beklediklerim)
        </div>

        <div class="content">
            <ul>
                @foreach(Auth::user()->waitForApproval as $approval)
                <li>
                    @if (isset($approval->getItem))
                        <a href="{{ route('myWaitingDemands') }}" class="clearfix">
                            <span class="message">Talep tipi : {{ $approval->getType() }}</span>
                            <span class="message">
                                Ürün : {{ $approval->getItem->code }} - {{ $approval->getItem->name }}
                            </span>
                            <span class="message">Miktar : {{ $approval->amount }} {{ $approval->getItem->itemToUnit->content }}</span>
                            <span class="message">
                                Hareket yönü :
                                <span class="badge badge-danger"> 
                                    {!! ($approval->getDwindlingWarehouse) ? '<i class="fas fa-warehouse"></i> '.$approval->getDwindlingWarehouse->name : '<i class="fas fa-truck-loading"></i>' !!}
                                </span>
                                <i class="fas fa-angle-double-right"></i>
                                <span class="badge badge-success">
                                    {!! ($approval->getIncreasedWarehouse) ? '<i class="fas fa-warehouse"></i> '.$approval->getIncreasedWarehouse->name : '<i class="fas fa-dolly-flatbed fa-lg"></i>' !!}
                                </span>                       
                            </span>
                        </a>
                    @endif
                </li>
                
                @endforeach
                <hr />
                <div class="text-end">
                    <a href="{{ route('myWaitingDemands') }}" class="view-more">Tümünü göster</a>
                </div>
            </ul>
        </div>
    </div>
</li>