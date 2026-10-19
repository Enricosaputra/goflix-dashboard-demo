<?php

namespace App\Services;

use App\Enums\AmountType;
use App\Models\Coupon;
use App\Models\User;
use Carbon\CarbonInterface;
use DomainException;

class CouponService
{
    /**
     * How much the coupon takes off a price for this user.
     *
     * @throws DomainException when the coupon cannot be used
     */
    public function discount(Coupon $coupon, int $price, User $user, CarbonInterface $on): int
    {
        if (! $coupon->is_active || $on->lt($coupon->starts_on) || $on->gt($coupon->ends_on->endOfDay())) {
            throw new DomainException(__('Coupon :code is not valid on this date.', ['code' => $coupon->code]));
        }
        if ($coupon->single_use && $coupon->usedBy($user)) {
            throw new DomainException(__(':name has already used coupon :code.', ['name' => $user->name, 'code' => $coupon->code]));
        }

        $discount = $coupon->amount_type === AmountType::Percent ? intdiv($price * $coupon->amount, 100) : $coupon->amount;

        return min($discount, $price);
    }
}
