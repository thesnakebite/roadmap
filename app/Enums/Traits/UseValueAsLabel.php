<?php

declare(strict_types=1);

namespace App\Enums\Traits;

use Illuminate\Contracts\Support\Htmlable;

trait UseValueAsLabel
{
    public function getLabel(): string|Htmlable|null
    {
        return $this->value;
    }
}
