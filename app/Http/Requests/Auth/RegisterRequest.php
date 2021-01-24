<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8|max:512',
        ];
    }

    /**
     * Get the validation messages.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => __('auth.name_required'),
            'email.required' => __('auth.email_required'),
            'email.email' => __('auth.email_invalid'),
            'email.unique' => __('auth.email_taken'),
            'password.required' => __('auth.password_required'),
            'password.confirmed' => __('auth.password_match'),
            'password.min' => __('auth.password_min'),
            'password.max' => __('auth.password_max'),
        ];
    }
}
