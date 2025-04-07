@php
    if (Session::has('success')) {
        $this->emit('alert', ['type' => 'success', 'message' => Session::get('success')]);
    }

    if (Session::has('error')) {
        $this->emit('alert', ['type' => 'error', 'message' => Session::get('error')]);
    }
@endphp
