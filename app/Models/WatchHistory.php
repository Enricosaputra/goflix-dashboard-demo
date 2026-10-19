<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class WatchHistory extends Model
{
    protected $table = 'watch_history';

    protected $fillable = ['user_id', 'watchable_type', 'watchable_id', 'stopped_at_seconds', 'created_at', 'updated_at'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function watchable(): MorphTo
    {
        return $this->morphTo();
    }

    /** How far into the movie or episode the user got, 0 to 100. */
    public function progress(): int
    {
        $seconds = ($this->watchable?->duration_minutes ?? 0) * 60;

        return $seconds > 0 ? min(100, (int) round($this->stopped_at_seconds / $seconds * 100)) : 0;
    }
}
