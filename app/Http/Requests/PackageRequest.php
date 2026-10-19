<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PackageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'price' => ['required', 'integer', 'min:1000'],
            'duration_months' => ['required', 'integer', 'between:1,24'],
            'max_devices' => ['required', 'integer', 'between:1,10'],
            'max_quality' => ['required', Rule::in(['480p', '720p', '1080p'])],
            'ads_free' => ['boolean'],
            'watch_on_tv' => ['boolean'],
            'is_active' => ['boolean'],
            'features' => ['array'],
            'features.*.name' => ['nullable', 'string', 'max:100'],
            'features.*.value' => ['nullable', 'string', 'max:100'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'ads_free' => $this->boolean('ads_free'), 'watch_on_tv' => $this->boolean('watch_on_tv'), 'is_active' => $this->boolean('is_active'),
        ]);
    }
}
