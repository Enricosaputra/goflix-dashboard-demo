<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

// A subscription plan.
class Package extends Model
{
    use HasFactory;

    // Same defaults as the database, so a model is right before it is reloaded.
    protected $attributes = ['is_active' => true, 'ads_free' => true, 'watch_on_tv' => true];

    protected $fillable = ['name', 'price', 'duration_months', 'max_devices', 'max_quality', 'ads_free', 'watch_on_tv', 'is_active'];

    protected $casts = ['ads_free' => 'boolean', 'watch_on_tv' => 'boolean', 'is_active' => 'boolean'];

    public function features(): HasMany
    {
        return $this->hasMany(PackageFeature::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /** @param  array<int, array{name?: ?string, value?: ?string}>  $rows */
    public function setFeatures(array $rows): void
    {
        $this->features()->delete();
        foreach ($rows as $row) {
            if (filled($row['name'] ?? null)) {
                $this->features()->create(['name' => trim($row['name']), 'value' => trim($row['value'] ?? '')]);
            }
        }
    }

    public function deletionBlocker(): ?string
    {
        return $this->subscriptions()->exists() ? __('":name" has been sold. Mark it inactive instead.', ['name' => $this->name]) : null;
    }
}
