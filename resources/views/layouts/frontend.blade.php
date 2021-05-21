@extends('layouts.base')

@section('content')
    <header>
        {{ __('menu.app_name') }}

        <a href="{{ route('home') }}">{{ __('menu.home') }}</a>&nbsp;
        <a href="{{ route('collectibles.index') }}">{{ __('menu.collectibles') }}</a>&nbsp;

        @auth
            <x-logout-link>{{ __('menu.logout') }}</x-logout-link>&nbsp;
        @else
            <a href="{{ route('login') }}">{{ __('menu.login') }}</a>&nbsp;
            <a href="{{ route('register') }}">{{ __('menu.register') }}</a>&nbsp;
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
