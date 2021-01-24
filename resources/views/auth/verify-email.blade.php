@section('title', __('auth.title.verify_email'))

<x-frontend-layout>
    <h1>{{ __('auth.title.verify_email') }}</h1>

    <x-redirect-status />
    <x-validation-errors :errors="$errors">{{ __('auth.error_heading') }}</x-validation-errors>

    <div>
        {{ __('auth.verify_email_message') }}
    </div>

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf

        <div>
            <x-forms.button type="submit">
                {{ __('auth.button.resend_verification_email') }}
            </x-forms.button>
        </div>
    </form>
</x-frontend-layout>
