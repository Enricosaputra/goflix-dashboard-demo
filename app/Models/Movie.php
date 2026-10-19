<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Movie extends Model
{
    use HasFactory;

    // Same defaults as the database, so a model is right before it is reloaded.
    protected $attributes = ['is_active' => true, 'is_premium' => false, 'is_downloadable' => false, 'rent_days' => 2, 'max_quality' => '1080p'];

    protected $fillable = [
        'title', 'description', 'language_id', 'release_year', 'duration_minutes', 'maturity_rating', 'imdb_rating',
        'is_premium', 'rent_price', 'rent_days', 'is_downloadable', 'max_quality', 'is_active',
    ];

    protected $casts = ['is_premium' => 'boolean', 'is_downloadable' => 'boolean', 'is_active' => 'boolean', 'imdb_rating' => 'float'];

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }

    public function people(): BelongsToMany
    {
        return $this->belongsToMany(Person::class);
    }

    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }

    public function watches(): MorphMany
    {
        return $this->morphMany(WatchHistory::class, 'watchable');
    }

    public function isRentable(): bool
    {
        return $this->is_premium && $this->rent_price !== null;
    }

    public function deletionBlocker(): ?string
    {
        return $this->rentals()->exists() || $this->watches()->exists()
            ? __('":name" has rentals or viewing history. Mark it inactive instead.', ['name' => $this->title])
            : null;
    }
}
