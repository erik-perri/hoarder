@extends('layouts.base')

@section('content')
    <header>
        {{ __('menu.app_name') }}

        <a href="{{ route('home') }}">{{ __('menu.home') }}</a>
    </header>

    <section id="page-content">
        {{ $slot }}
    </section>

    @if (isset($footer))
        <footer>
            {{ $footer }}
        </footer>
    @endif
@endsection
