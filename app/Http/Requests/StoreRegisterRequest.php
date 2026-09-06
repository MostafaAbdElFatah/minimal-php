<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreRegisterRequest extends FormRequest
{

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'first_name' => trim($this->input('first_name', '')),
            'last_name' => trim($this->input('last_name', '')),
            'email' => trim($this->input('email', '')),
            'password' => trim($this->input('password', '')),
            'password_confirmation' => trim($this->input('password_confirmation', '')),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<mixed>|string>
     */
    public function rules(): array
    {
        return [
           'first_name' => ['required', 'string', 'min:2', 'max:100'],
           'last_name' => ['required', 'string', 'min:2', 'max:100'],
           'email' => ['required', 'string', 'email', 'max:255'],
           'password' => ['required', 'string', 'confirmed', Password::default()], // or 'confirmed:confirm_password'
        ];
    }
}