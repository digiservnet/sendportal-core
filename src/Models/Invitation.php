<?php

declare(strict_types=1);

namespace Sendportal\Base\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invitation extends Model
{
    public $incrementing = false;

    protected $guarded = [];

    public function getExpiresAtAttribute(): Carbon
    {
        return $this->created_at->addWeek();
    }

    /**
     * The team this invitation is for.
     *
     * @return BelongsTo
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function isExpired(): bool
    {
        return Carbon::now()->gte($this->expires_at);
    }

    public function isNotExpired(): bool
    {
        return !$this->isExpired();
    }
}