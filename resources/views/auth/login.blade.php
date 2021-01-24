@section('title', __('auth.title.login'))

<x-frontend-layout>
    <h1>{{ __('auth.title.login') }}</h1>

    <x-validation-errors :errors="$errors">{{ __('auth.error_heading') }}</x-validation-errors>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <x-forms.input-email name="email"
                             value="{{ old('email') }}"
                             label="{{ __('auth.label.email') }}"
                             required
                             autocomplete="email" />

        <x-forms.input-password name="password"
                                value=""
                                label="{{ __('auth.label.password') }}"
                                required
                                autocomplete="current-password" />

        <div>
            <x-forms.input-checkbox name="remember" label="{{ __('auth.label.remember') }}" />
        </div>

        <div>
            @if (Route::has('password.forgot'))
                <a href="{{ route('password.forgot') }}">{{ __('auth.forgot_password') }}</a>
            @endif

            <x-forms.button>
                {{ __('auth.button.login') }}
            </x-forms.button>
        </div>
    </form>
</x-frontend-layout>
