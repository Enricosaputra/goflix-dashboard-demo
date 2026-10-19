<?php

namespace App\Http\Requests;

use App\Enums\LoginType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            // A plain pattern instead of Laravel's `email` rule: Laravel 10 has an unpatched advisory for that rule
            // (CRLF injection). \s also rules out line breaks.
            'email' => ['required', 'string', 'max:191', 'regex:/^[^@\s]+@[^@\s]+\.[^@\s]+$/D', Rule::unique('users')->ignore($this->route('user'))],
            'login_type' => ['required', Rule::enum(LoginType::class)],
            'is_active' => ['boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['is_active' => $this->boolean('is_active')]);
    }
}
