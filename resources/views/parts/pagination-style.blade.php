@if (Auth::user()->theme)
    <style>
        [rel='prev'],
        [rel='next'] {
            background-color: #282d36 !important;
            border-color: #343a40;
        }

        .pagination>li :not([rel='prev'], [rel='next'], [aria-current] .page-link) {
            background-color: #282d36 !important;
            color: #0088cc;
            border-color: #343a40;
        }
    </style>
@endif
