<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MovieRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:191'],
            'description' => ['nullable', 'string', 'max:5000'],
            'language_id' => ['required', 'exists:languages,id'],
            'genres' => ['required', 'array', 'min:1'],
            'genres.*' => ['exists:genres,id'],
            'people' => ['array'],
            'people.*' => ['exists:people,id'],
            'release_year' => ['required', 'integer', 'between:1900,2100'],
            'duration_minutes' => ['required', 'integer', 'between:1,600'],
            'maturity_rating' => ['nullable', Rule::in(['SU', '13+', '17+', '21+'])],
            'imdb_rating' => ['nullable', 'numeric', 'between:0,10'],
            'is_premium' => ['boolean'],
            // Only a premium movie can be rented; a free one is already free.
            'rent_price' => ['nullable', 'integer', 'min:1000', 'prohibited_unless:is_premium,true'],
            'rent_days' => ['required', 'integer', 'between:1,30'],
            'is_downloadable' => ['boolean'],
            'max_quality' => ['required', Rule::in(['480p', '720p', '1080p'])],
            'is_active' => ['boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_premium' => $this->boolean('is_premium'),
            'is_downloadable' => $this->boolean('is_downloadable'),
            'is_active' => $this->boolean('is_active'),
            'rent_price' => $this->input('rent_price') ?: null,
        ]);
    }
}
