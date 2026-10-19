<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

// A series. Its episodes are grouped by the shared season names.
class Show extends Model
{
    // Same defaults as the database, so a model is right before it is reloaded.
    protected $attributes = ['is_active' => true, 'is_premium' => false];

    protected $fillable = ['title', 'description', 'language_id', 'release_year', 'maturity_rating', 'imdb_rating', 'is_premium', 'is_active'];

    protected $casts = ['is_premium' => 'boolean', 'is_active' => 'boolean', 'imdb_rating' => 'float'];

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }

    public function episodes(): HasMany
    {
        return $this->hasMany(Episode::class)->orderBy('season_id')->orderBy('episode_number');
    }

    /**
     * Replace the episode list. Rows without a title are ignored.
     *
     * @param  array<int, array{season_id?: int|string, episode_number?: int|string, title?: ?string, duration_minutes?: int|string, is_premium?: mixed}>  $rows
     */
    public function setEpisodes(array $rows): void
    {
        $keep = Collection::make($rows)->filter(fn ($row) => filled($row['title'] ?? null));

        // Update episodes that still exist so their viewing history keeps pointing at them.
        $existing = $this->episodes()->get()->keyBy(fn (Episode $e) => "{$e->season_id}-{$e->episode_number}");
        $seen = [];
        foreach ($keep as $row) {
            $key = "{$row['season_id']}-{$row['episode_number']}";
            $seen[] = $key;
            $values = [
                'title' => trim($row['title']),
                'duration_minutes' => (int) $row['duration_minutes'],
                'is_premium' => ! empty($row['is_premium']),
            ];
            isset($existing[$key])
                ? $existing[$key]->update($values)
                : $this->episodes()->create($values + ['season_id' => $row['season_id'], 'episode_number' => $row['episode_number']]);
        }
        // reject() rather than except(): on an Eloquent collection except() filters by primary key.
        $existing->reject(fn (Episode $episode, string $key) => in_array($key, $seen, true))->each->delete();
    }

    public function deletionBlocker(): ?string
    {
        $watched = WatchHistory::where('watchable_type', Episode::class)->whereIn('watchable_id', $this->episodes()->pluck('id'))->exists();

        return $watched ? __('":name" has viewing history. Mark it inactive instead.', ['name' => $this->title]) : null;
    }
}
