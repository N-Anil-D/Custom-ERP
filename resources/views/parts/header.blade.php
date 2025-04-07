{{-- start: header --}}
<header class="header">
    <div class="logo-container">
        <a href="{{ url('/') }}" class="logo">
            @if(Auth::user()->theme)
                <img src="{{ url('img/logo-1.png') }}" width="100%" height="30" alt="Invamed Admin" />
            @else
                <img src="{{ url('img/logo.webp') }}" width="100%" height="35" alt="Invamed Admin" />
            @endif
        </a>

        <div class="d-md-none toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
            <i class="fas fa-bars" aria-label="Toggle sidebar"></i>
        </div>

    </div>

    {{-- start: notification & user box --}}
    <div class="header-right">
        <ul class="notifications">
            {{-- açılan talepler --}}
            <livewire:part.wait-approval>
            {{-- onay verilecek talepler --}}
            <livewire:part.notification>
        </ul>

        <span class="separator"></span>

        <div id="userbox" class="userbox">
            <a href="#" data-bs-toggle="dropdown">
                <figure class="profile-picture">
                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&color=7F9CF5&background=EBF4FF" alt="{{ Auth::user()->name }}" alt="Joseph Doe" class="rounded-circle" data-lock-picture="img/!logged-user.jpg') }}" />
                </figure>
                <div class="profile-info" data-lock-name="{{ Auth::user()->name }}" data-lock-email="{{ Auth::user()->email }}">
                    <span class="name">{{ Auth::user()->name }}</span>
                    <span class="role">{{ Auth::user()->tel_no }}</span>
                </div>

                <i class="fa custom-caret"></i>
            </a>

            <div class="dropdown-menu">
                <ul class="list-unstyled mb-2">
                    <li class="divider"></li>
                    <li>
                        <a role="menuitem" tabindex="-1" href="{{ route('profile.show') }}"><i class="bx bx-user-circle"></i> Hesabım</a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('portal.theme') }}">
                        @if(Auth::user()->theme)
                            @csrf
                            <input type="hidden" name="theme" value="0">
                            <a role="menuitem" tabindex="-1" href="#" onclick="event.preventDefault();
                            this.closest('form').submit();">
                                <i class='bx bx-sun bx-spin'></i> Aydınlık Mod
                            </a>
                        @else
                            @csrf
                            <input type="hidden" name="theme" value="1">
                            <a role="menuitem" tabindex="-1" href="#" onclick="event.preventDefault();
                            this.closest('form').submit();">
                                <i class='bx bxs-moon bx-tada'></i> Karanlık Mod
                            </a>
                        @endif
                        </form>
                    </li>
                    <li>
                        <a role="menuitem" href="{{ route('psbox') }}"><i class='bx bxs-key'></i> Parola Kutum</a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a role="menuitem" tabindex="-1" href="{{ route('logout') }}" onclick="event.preventDefault();
                            this.closest('form').submit();"><i class="bx bx-power-off"></i> Oturumu kapat</a>
                        </form>
                    </li>
                </ul>
            </div>

        </div>

    </div>
    {{-- end: notification & user box --}}
</header>
{{-- end: header --}}
