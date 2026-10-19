<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ShowRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:191'],
            'description' => ['nullable', 'string', 'max:5000'],
            'language_id' => ['required', 'exists:languages,id'],
            'genres' => ['required', 'array', 'min:1'],
            'genres.*' => ['exists:genres,id'],
            'release_year' => ['required', 'integer', 'between:1900,2100'],
            'maturity_rating' => ['nullable', Rule::in(['SU', '13+', '17+', '21+'])],
            'imdb_rating' => ['nullable', 'numeric', 'between:0,10'],
            'is_premium' => ['boolean'],
            'is_active' => ['boolean'],
            'episodes' => ['array'],
            'episodes.*.title' => ['nullable', 'string', 'max:191'],
            'episodes.*.season_id' => ['required_with:episodes.*.title', 'nullable', 'exists:seasons,id'],
            'episodes.*.episode_number' => ['required_with:episodes.*.title', 'nullable', 'integer', 'between:1,999'],
            'episodes.*.duration_minutes' => ['required_with:episodes.*.title', 'nullable', 'integer', 'between:1,600'],
        ];
    }

    // The same season and episode number cannot appear twice.
    public function after(): array
    {
        return [function (Validator $validator) {
            $keys = collect($this->input('episodes', []))
                ->filter(fn ($row) => filled($row['title'] ?? null))
                ->map(fn ($row) => ($row['season_id'] ?? '').'-'.($row['episode_number'] ?? ''));
            if ($keys->count() !== $keys->unique()->count()) {
                $validator->errors()->add('episodes', __('Each season can only have one episode with a given number.'));
            }
        }];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['is_premium' => $this->boolean('is_premium'), 'is_active' => $this->boolean('is_active')]);
    }
}
