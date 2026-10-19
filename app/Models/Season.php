<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Season extends Model
{
    protected $fillable = ['name'];

    public function episodes(): HasMany
    {
        return $this->hasMany(Episode::class);
    }

    public function deletionBlocker(): ?string
    {
        return $this->episodes()->exists() ? __('":name" has episodes.', ['name' => $this->name]) : null;
    }
}
