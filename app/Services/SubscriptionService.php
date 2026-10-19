<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\User;
use Carbon\CarbonInterface;
use DomainException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubscriptionService
{
    public function __construct(private CouponService $coupons) {}

    /**
     * Sell a plan to a user. Buying while still subscribed adds the time on top of what is left,
     * so a renewal never wastes paid days. $at is the moment of payment (now, unless importing).
     *
     * @throws DomainException when the plan is inactive or the coupon cannot be used
     */
    public function subscribe(User $user, Package $package, ?Coupon $coupon = null, ?string $reference = null, ?CarbonInterface $at = null): Subscription
    {
        $at ??= now();
        if (! $package->is_active) {
            throw new DomainException(__('Plan :name is no longer sold.', ['name' => $package->name]));
        }

        return DB::transaction(function () use ($user, $package, $coupon, $reference, $at) {
            $user = User::lockForUpdate()->findOrFail($user->id); // two payments at once must not overlap
            $discount = $coupon ? $this->coupons->discount($coupon, $package->price, $user, $at) : 0;

            $today = $at->copy()->startOfDay();
            $startsOn = $user->subscription_ends_on?->gte($today) ? $user->subscription_ends_on->copy()->addDay() : $today;
            $endsOn = $startsOn->copy()->addMonthsNoOverflow($package->duration_months)->subDay();

            $subscription = $user->subscriptions()->create([
                'package_id' => $package->id,
                'coupon_id' => $coupon?->id,
                'price' => $package->price,
                'discount' => $discount,
                'amount' => $package->price - $discount,
                'payment_reference' => $reference ?? 'MANUAL-'.Str::upper(Str::random(8)),
                'starts_on' => $startsOn,
                'ends_on' => $endsOn,
                'created_at' => $at,
            ]);
            $user->update(['subscription_ends_on' => $endsOn]);

            return $subscription;
        });
    }
}
