<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - {{ __('menu.app_name') }}</title>
    @section('styles')
        <link rel="stylesheet" href="{{ mix('/css/app.css') }}" />
    @show
</head>
<body>

<div id="app">
    @yield('content')
</div>

@section('scripts')
    <script type="text/javascript" src="{{ mix('/js/app.js') }}"></script>
@show
</body>
</html>
