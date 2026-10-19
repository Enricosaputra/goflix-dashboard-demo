<?php

namespace App\Models;

use App\Enums\AmountType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    // Same defaults as the database, so a model is right before it is reloaded.
    protected $attributes = ['is_active' => true, 'single_use' => false];

    protected $fillable = ['code', 'amount_type', 'amount', 'starts_on', 'ends_on', 'single_use', 'is_active'];

    protected $casts = [
        'amount_type' => AmountType::class, 'starts_on' => 'date', 'ends_on' => 'date',
        'single_use' => 'boolean', 'is_active' => 'boolean',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }

    public function usedBy(User $user): bool
    {
        return $this->subscriptions()->where('user_id', $user->id)->exists()
            || $this->rentals()->where('user_id', $user->id)->exists();
    }

    public function label(): string
    {
        return $this->amount_type === AmountType::Percent ? "{$this->amount}%" : rupiah($this->amount);
    }

    public function deletionBlocker(): ?string
    {
        return $this->subscriptions()->exists() || $this->rentals()->exists()
            ? __('":name" has been used. Mark it inactive instead.', ['name' => $this->code])
            : null;
    }
}
