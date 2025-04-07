<div>
    <!-- start: sidebar -->
    <aside id="sidebar-left" class="sidebar-left">

        <div class="sidebar-header">
            <div class="sidebar-title">
                Menu
            </div>
            <a wire:click="navbar()">
                <div class="sidebar-toggle d-none d-md-block" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
                    <i class="fas fa-bars" aria-label="Toggle sidebar"></i>
                </div>
            </a>
        </div>

        <div class="nano">
            <div class="nano-content">
                <nav id="menu" class="nav-main" role="navigation">

                    <ul class="nav nav-main">

                        @foreach ($sideBar->where('hid',0) as $side)
                            @if(in_array($side->id, $userBar->usrToAut->pluck('urlId')->toArray()))
                                @if ($side->sidToSub->count()==0)
                                    {{-- 1. katman elemansız--}}
                                    <li class="{{ (Request::segment(1) == $side->link) ? 'nav-active' : '' }}">
                                        <a class="nav-link" href="{{ url($side->link) }}">
                                            {!! $side->icon !!}
                                            <span>{{ $side->name }}</span>
                                        </a>
                                    </li>

                                @else
                                    {{-- 1. katman elemanlı --}}
                                    <li class="nav-parent {{ (Request::segment(1) == $side->link) ? 'nav-expanded nav-active' : '' }}">
                                        <a class="nav-link" href="#">
                                            {!! $side->icon !!}
                                            <span>{{ $side->name }}</span>
                                        </a>

                                        <ul class="nav nav-children">
                                        @foreach($sideBar->where('hid',$side->id) as $sub1)
                                            @if($sub1->sidToSub->count()==0)
                                            {{-- 2. katman elemansız --}}
                                            <li class="{{ (request()->path() == $sub1->link) ? 'nav-active' : '' }}">
                                                <a class="nav-link" href="{{ url($sub1->link) }}">{!! $sub1->icon !!} {{ $sub1->name }}</a>
                                            </li>
                                            @else
                                            {{-- 2. katman elemanlı --}}
                                            <li class="nav-parent {{ (request()->segment(1).'/'.request()->segment(2) == $sub1->link) ? 'nav-expanded' : '' }}">
                                                <a class="nav-link" href="#">{!! $sub1->icon !!} {{ $sub1->name }}</a>
                                                <ul class="nav nav-children">
                                                    @foreach($sideBar->where('hid',$sub1->id) as $sub2)
                                                        @if($sub2->sidToSub->count()==0)
                                                        {{-- 3. katman elemansız --}}
                                                        <li class="{{ (request()->path() == $sub2->link) ? 'nav-active' : '' }}">
                                                            <a class="nav-link" href="{{ url($sub2->link) }}">
                                                                {!! $sub2->icon !!} {{ $sub2->name }}
                                                            </a>
                                                        </li>
                                                        @else
                                                        {{-- 3. katman elemanlı --}}
                                                        <li class="nav-parent {{ (request()->segment(1).'/'.request()->segment(2).'/'.request()->segment(3) == $sub2->link) ? 'nav-expanded' : ''}}">
                                                            <a class="nav-link" href="#">
                                                                {!! $sub2->icon !!} {{ $sub2->name }}
                                                            </a>
                                                            <ul class="nav nav-children">
                                                                @foreach($sideBar->where('hid',$sub2->id) as $sub3)
                                                                {{-- 4. katman --}}
                                                                <li class="{{ (request()->path() == $sub3->link) ? 'nav-active' : '' }}">
                                                                    <a class="nav-link" href="{{ url($sub3->link) }}">
                                                                        {!! $sub3->icon !!} {{ $sub3->name }}
                                                                    </a>
                                                                </li>
                                                                @endforeach
                                                            </ul>
                                                        </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </li>

                                            @endif
                                        @endforeach
                                        </ul>

                                    </li>
                                @endif
                            @endif
                        @endforeach

                    </ul>

                </nav>

            </div>

        </div>

    </aside>
    <!-- end: sidebar -->

</div>
