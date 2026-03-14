<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Feature\FeatureStatus;
use App\Enums\Feature\FeatureType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Feature extends Model
{
    /** @use HasFactory<\Database\Factories\FeatureFactory> */
    use HasFactory;

    protected function casts()
    {
        return [
            'status' => FeatureStatus::class,
            'type' => FeatureType::class,
        ];
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(Milestone::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }
}
