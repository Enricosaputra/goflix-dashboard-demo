<?php

namespace App\Http\Requests;

use App\Enums\AmountType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CouponRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50', Rule::unique('coupons')->ignore($this->route('coupon'))],
            'amount_type' => ['required', Rule::enum(AmountType::class)],
            'amount' => ['required', 'integer', 'min:1', Rule::when($this->input('amount_type') === 'percent', ['max:100'])],
            'starts_on' => ['required', 'date'],
            'ends_on' => ['required', 'date', 'after_or_equal:starts_on'],
            'single_use' => ['boolean'],
            'is_active' => ['boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'code' => strtoupper((string) $this->input('code')),
            'single_use' => $this->boolean('single_use'),
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
