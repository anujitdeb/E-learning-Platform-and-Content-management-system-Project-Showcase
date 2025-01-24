<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait ManagesActiveStatus
{
    /**
     * Scope a query to only include active models.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include inactive models.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('is_active', false);
    }
}
