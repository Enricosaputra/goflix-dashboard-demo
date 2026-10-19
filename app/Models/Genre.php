<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Genre extends Model
{
    use HasFactory;

    // Same defaults as the database, so a model is right before it is reloaded.
    protected $attributes = ['is_active' => true];

    protected $fillable = ['name', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function movies(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class);
    }

    public function shows(): BelongsToMany
    {
        return $this->belongsToMany(Show::class);
    }

    public function deletionBlocker(): ?string
    {
        return $this->movies()->exists() || $this->shows()->exists()
            ? __('":name" is used by movies or series. Mark it inactive instead.', ['name' => $this->name])
            : null;
    }
}
