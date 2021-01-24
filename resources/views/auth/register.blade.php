@section('title', __('auth.title.register'))

<x-frontend-layout>
    <h1>{{ __('auth.title.register') }}</h1>

    <x-validation-errors :errors="$errors">{{ __('auth.error_heading') }}</x-validation-errors>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <x-forms.input-text name="name"
                            value="{{ old('name') }}"
                            label="{{ __('auth.label.name') }}"
                            required
                            autocomplete="nickname" />

        <x-forms.input-email name="email"
                             value="{{ old('email') }}"
                             label="{{ __('auth.label.email') }}"
                             required
                             autocomplete="email" />

        <x-forms.input-password name="password"
                                value=""
                                label="{{ __('auth.label.password') }}"
                                required
                                autocomplete="new-password" />

        <x-forms.input-password name="password_confirmation"
                                value=""
                                label="{{ __('auth.label.password_confirmation') }}"
                                required />

        <div>
            <a href="{{ route('login') }}">
                {{ __('auth.already_registered') }}
            </a>

            <x-forms.button>
                {{ __('auth.button.register') }}
            </x-forms.button>
        </div>
    </form>
</x-frontend-layout>
