@extends('layouts.base')

@section('content')
    <header>
        {{ __('menu.app_name') }}

        <a href="{{ route('home') }}">{{ __('menu.home') }}</a>
        <a href="{{ route('collectibles.index') }}">{{ __('menu.collectibles') }}</a>

        @auth
            <x-logout-link>{{ __('menu.logout') }}</x-logout-link>
        @else
            <a href="{{ route('login') }}">{{ __('menu.login') }}</a>
            <a href="{{ route('register') }}">{{ __('menu.register') }}</a>
        @endif
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
