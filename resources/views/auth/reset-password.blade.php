@section('title', __('auth.title.reset_password'))

<x-frontend-layout>
    <h1>{{ __('auth.title.reset_password') }}</h1>

    <x-validation-errors :errors="$errors">{{ __('auth.error_heading') }}</x-validation-errors>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <x-forms.input-email name="email"
                             value="{{ old('email', $request->email) }}"
                             label="{{ __('auth.label.email') }}"
                             readonly
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
            <x-forms.button>
                {{ __('auth.button.reset_password') }}
            </x-forms.button>
        </div>
    </form>
</x-frontend-layout>
