<div>
    <div class="row">
        <div class="col-lg-7 mb-3">
            <div class="card-body">

                <div class="right-wrapper">
                    <div class="position-relative">
                        <div class="position-absolute top-0 end-0">
                            <form method="POST" action="{{ route('portal.theme') }}">
                                @if(Auth::user()->theme)
                                    @csrf
                                    <input type="hidden" name="theme" value="0">
                                    <a class="btn btn-default btn-xs" href="#" onclick="event.preventDefault();
                                    this.closest('form').submit();">
                                        <i class='bx bx-sun bx-spin'></i> Aydınlık modu dene
                                    </a>
                                @else
                                    @csrf
                                    <input type="hidden" name="theme" value="1">
                                    <a class="btn btn-default btn-xs" href="#" onclick="event.preventDefault();
                                    this.closest('form').submit();">
                                        <i class='bx bxs-moon bx-tada'></i> Karanlık modu dene
                                    </a>
                                @endif
                            </form>
                        </div>
                    </div>
                    <p class="text-right">Sayın <strong>{{ Auth::user()->name }}</strong> CustomERP'a hoşgeldiniz.</p>
                </div>

                <hr>
                
                <div class="timeline">
                    <div class="tm-body">
                        <div class="tm-title">
                            <h5 class="m-0 pt-2 pb-2">CustomERP / Tanıtım</h5>
                        </div>
                        <ol class="tm-items">
                            
                            @foreach($section as $row)
                            <li>
                                <div class="tm-info">
                                    @switch($row->type)
                                        @case(0)
                                            <div class="tm-icon" style="color: red;"><i class="fas fa-times-circle"></i></div>
                                            @break
                                        @case(1)
                                            <div class="tm-icon" style="color: green;"><i class="fas fa-check-circle"></i></div>
                                            @break
                                        @default
                                            <div class="tm-icon"><i class="fas fa-question-circle"></i></div>
                                    @endswitch
                                    <time class="tm-datetime">
                                        <div class="tm-datetime-date">{{ $row->content }}</div>
                                    </time>
                                </div>
                                <div class="tm-box" data-appear-animation="{{ $row->animation }}" data-appear-animation-delay="{{ $row->animationDelay }}">
                                    <p>
                                        @foreach($row->secToDet as $det)
                                            @if($det->type == 0)
                                                <span style="color: red;">
                                            @endif
                                            <i class='bx bxs-share bx-flip-horizontal' ></i> {{ $det->content }}
                                                @if($det->type == 1)
                                                <span style="color: greenyellow;">[<i class="fas fa-check"></i>]</span>
                                                @endif
                                            @if($det->type == 0)
                                                [<i class="fas fa-times"></i>]
                                                </span>
                                            @endif
                                            <br>
                                        @endforeach
                                    </p>
                                    @if($row->author)
                                    <div class="tm-meta">
                                        <span>
                                            <i class="bx bx-user"></i> {{ $row->author }}
                                        </span>
                                    </div>
                                    @endif
                                </div>
                            </li>
                            @endforeach
                            
                            
                        </ol>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5 mb-3">
            <div class="card-body">
                <div class="right-wrapper">
                    <div class="position-relative">
                        <div class="position-absolute top-0 end-0">BOT Chat</div>
                    </div>
                    <p>Telegram üzerinden <a href="https://web.telegram.org/#@CustomERPshowcaseBOT" target="_blank">@CustomERPshowcaseBOT</a> a yazılan mesajlar</p>
                </div>
                <hr>
                <div class="scrollable visible-slider colored-slider" data-plugin-scrollable style="height: 775px;">
                    <div class="scrollable-content">
                        @if($data['ok'])
                            @php
                                $chat = collect($data['result'])->reverse()->toArray();
                            @endphp
                                <div wire:poll.10s>
                                    <div class="timeline timeline-simple mt-3 mb-3">
                                        <div class="tm-body">
                                            @foreach($chat as $row)
                                                @if(isset($row['message']))
                                                    <ol class="tm-items">
                                                        <li>
                                                            <div class="tm-box">
                                                                <p class="text-muted mb-0">{{ (Auth::user()->getUserName($row['message']['from']['id'])) ? Auth::user()->getUserName($row['message']['from']['id'])->name : 'Tanımsız kullanıcı' }}  ({{ \Carbon\Carbon::createFromTimestamp($row['message']['date'])->diffForHumans() }})</p>
                                                                <p>{{ (isset($row['message']['text'])) ? preg_replace('/[^A-Za-z0-9\-ÜİÇÖĞIığüşöç.,\/]/', ' ', $row['message']['text']) : 'Tanımsız içerik' }}</p>
                                                            </div>
                                                        </li>
                                                    </ol>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                        @endif
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
</div>
