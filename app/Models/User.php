<?php

namespace App\Models;

use App\Enums\LoginType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

// A viewer of the app (not a dashboard admin).
class User extends Model
{
    use HasFactory;

    // Same defaults as the database, so a model is right before it is reloaded.
    protected $attributes = ['is_active' => true];

    protected $fillable = ['name', 'email', 'login_type', 'subscription_ends_on', 'is_active'];

    protected $casts = ['login_type' => LoginType::class, 'subscription_ends_on' => 'date', 'is_active' => 'boolean'];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class)->latest('id');
    }

    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class)->latest('id');
    }

    public function watchHistory(): HasMany
    {
        return $this->hasMany(WatchHistory::class)->latest('updated_at');
    }

    public function bookmarks(): HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    public function hasActiveSubscription(): bool
    {
        return $this->subscription_ends_on !== null && $this->subscription_ends_on->gte(today());
    }

    public function activeRentalFor(Movie $movie): ?Rental
    {
        return $this->rentals()->where('movie_id', $movie->id)->where('expires_at', '>', now())->first();
    }

    public function deletionBlocker(): ?string
    {
        return $this->subscriptions()->exists() || $this->rentals()->exists()
            ? __('":name" has payments on record. Deactivate the account instead.', ['name' => $this->name])
            : null;
    }
}
