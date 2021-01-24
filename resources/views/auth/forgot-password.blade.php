@section('title', __('auth.title.forgot_password'))

<x-frontend-layout>
    <h1>{{ __('auth.title.forgot_password') }}</h1>

    <x-redirect-status />
    <x-validation-errors :errors="$errors">{{ __('auth.error_heading') }}</x-validation-errors>

    <div>
        {{ __('auth.forgot_password_message') }}
    </div>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <x-forms.input-email name="email"
                             value="{{ old('email') }}"
                             label="{{ __('auth.label.email') }}"
                             required
                             autocomplete="email" />

        <div>
            <x-forms.button>
                {{ __('auth.button.send_reset_link') }}
            </x-forms.button>
        </div>
    </form>
</x-frontend-layout>
