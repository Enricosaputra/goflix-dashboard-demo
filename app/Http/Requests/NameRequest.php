<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// Genres, languages and seasons only have a name (and an active flag).
class NameRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'is_active' => ['boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['is_active' => $this->boolean('is_active')]);
    }
}
