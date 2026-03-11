<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Milestone extends Model
{
    public function feature(): BelongsTo
    {
        return $this->belongsTo(Feature::class);
    }
}
