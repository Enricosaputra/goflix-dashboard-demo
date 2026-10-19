<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Episode extends Model
{
    // Same defaults as the database, so a model is right before it is reloaded.
    protected $attributes = ['is_premium' => false];

    protected $fillable = ['show_id', 'season_id', 'episode_number', 'title', 'duration_minutes', 'is_premium'];

    protected $casts = ['is_premium' => 'boolean'];

    public function show(): BelongsTo
    {
        return $this->belongsTo(Show::class);
    }

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    public function watches(): MorphMany
    {
        return $this->morphMany(WatchHistory::class, 'watchable');
    }

    /** An episode is premium on its own, or because the whole series is. */
    public function isPremium(): bool
    {
        return $this->is_premium || $this->show->is_premium;
    }

    public function label(): string
    {
        return "{$this->show->title}, {$this->season->name} E{$this->episode_number}: {$this->title}";
    }
}
