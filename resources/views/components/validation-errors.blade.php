@props(['errors'])

@if ($errors->any())
    <div {{ $attributes }}>
        @if ($slot)
        <div>
            {{ $slot }}
        </div>
        @endif

        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
