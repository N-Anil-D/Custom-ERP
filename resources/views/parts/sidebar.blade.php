@foreach ($sideBar as $side)
    @if (!$side->type)
        <li>
            <a class="nav-link" href="{{ url('settings/sidebar') }}">
                {!! $side->icon !!}
                <span>{{ $side->name }}</span>
            </a>
        </li>
    @else
        <li class="nav-parent">
            <a class="nav-link" href="{{ $side->link }}">

                {!! $side->icon !!}
                <span>{{ $side->name }}</span>
            </a>
        </li>
    @endif
@endforeach
