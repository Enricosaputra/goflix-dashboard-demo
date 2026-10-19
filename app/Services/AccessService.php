<?php

namespace App\Services;

use App\Models\Episode;
use App\Models\Movie;
use App\Models\User;

// The rule the Go API applies before it streams a video. The dashboard shows it per user,
// so support staff can answer "why can't I watch this?".
class AccessService
{
    /** @return array{allowed: bool, reason: string} */
    public function check(User $user, Movie|Episode $content): array
    {
        $active = $content instanceof Movie ? $content->is_active : $content->show->is_active;
        $premium = $content instanceof Movie ? $content->is_premium : $content->isPremium();

        if (! $user->is_active || ! $active) {
            return $this->deny(__('The account or the title is inactive.'));
        }
        if (! $premium) {
            return $this->allow(__('Free to watch.'));
        }
        if ($user->hasActiveSubscription()) {
            return $this->allow(__('Subscription active until :date.', ['date' => $user->subscription_ends_on->translatedFormat('j M Y')]));
        }
        if ($content instanceof Movie && $rental = $user->activeRentalFor($content)) {
            return $this->allow(__('Rented until :date.', ['date' => $rental->expires_at->translatedFormat('j M Y H:i')]));
        }

        return $this->deny($content instanceof Movie && $content->isRentable()
            ? __('Premium: needs a subscription, or a rental for :price.', ['price' => rupiah($content->rent_price)])
            : __('Premium: needs a subscription.'));
    }

    private function allow(string $reason): array
    {
        return ['allowed' => true, 'reason' => $reason];
    }

    private function deny(string $reason): array
    {
        return ['allowed' => false, 'reason' => $reason];
    }
}
