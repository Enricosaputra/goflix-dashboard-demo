<?php

namespace App\Enums;

enum AmountType: string
{
    case Fixed = 'fixed';
    case Percent = 'percent';

    public function label(): string
    {
        return $this === self::Fixed ? __('Fixed amount (Rp)') : __('Percentage (%)');
    }
}
