<form style="display: inline" method="POST" action="{{ route('logout') }}">
    @csrf

    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
        {{ $slot }}
    </a>
</form>
