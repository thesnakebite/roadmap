<?php

declare(strict_types=1);

namespace App\Enums\Feature;

use App\Enums\Traits\UseValueAsLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum FeatureType: string implements HasColor, HasIcon, HasLabel
{
    use UseValueAsLabel;

    case Feature = 'Feature';
    case Bug = 'Bug';

    public function getColor(): string|array|null
    {
        return match ($this) {
            FeatureType::Feature => 'info',
            FeatureType::Bug => 'warning',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Feature => 'heroicon-o-star',
            self::Bug => 'heroicon-o-bug-ant',
        };
    }
}
