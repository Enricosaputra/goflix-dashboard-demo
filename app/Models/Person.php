<?php

namespace App\Models;

use App\Enums\PersonRole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Person extends Model
{
    protected $table = 'people';

    // Same defaults as the database, so a model is right before it is reloaded.
    protected $attributes = ['is_active' => true];

    protected $fillable = ['name', 'role', 'is_active'];

    protected $casts = ['role' => PersonRole::class, 'is_active' => 'boolean'];

    public function movies(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class);
    }

    public function deletionBlocker(): ?string
    {
        return $this->movies()->exists() ? __('":name" is credited in movies. Mark them inactive instead.', ['name' => $this->name]) : null;
    }
}
