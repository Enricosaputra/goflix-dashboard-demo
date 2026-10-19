<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Movie;
use App\Models\Rental;
use App\Models\User;
use Carbon\CarbonInterface;
use DomainException;
use Illuminate\Support\Facades\DB;

class RentalService
{
    public function __construct(private CouponService $coupons) {}

    /**
     * Rent one premium movie for a few days.
     *
     * @throws DomainException when renting makes no sense or is not allowed
     */
    public function rent(User $user, Movie $movie, ?Coupon $coupon = null, ?CarbonInterface $at = null): Rental
    {
        $at ??= now();

        if (! $movie->is_active || ! $movie->isRentable()) {
            throw new DomainException(__(':title cannot be rented.', ['title' => $movie->title]));
        }
        if ($user->subscription_ends_on?->gte($at->copy()->startOfDay())) {
            throw new DomainException(__(':name already has a subscription that includes this movie.', ['name' => $user->name]));
        }
        $active = $user->rentals()->where('movie_id', $movie->id)->where('expires_at', '>', $at)->first();
        if ($active) {
            throw new DomainException(__(':name is already renting :title until :date.', [
                'name' => $user->name, 'title' => $movie->title, 'date' => $active->expires_at->translatedFormat('j M Y H:i'),
            ]));
        }

        return DB::transaction(function () use ($user, $movie, $coupon, $at) {
            $discount = $coupon ? $this->coupons->discount($coupon, $movie->rent_price, $user, $at) : 0;

            return $user->rentals()->create([
                'movie_id' => $movie->id,
                'coupon_id' => $coupon?->id,
                'price' => $movie->rent_price,
                'discount' => $discount,
                'amount' => $movie->rent_price - $discount,
                'expires_at' => $at->copy()->addDays($movie->rent_days),
                'created_at' => $at,
            ]);
        });
    }
}
