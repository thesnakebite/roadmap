<?php

use Livewire\Attributes\Computed;
use Livewire\Component;
use App\Models\Feature;
use App\Enums\Feature\FeatureStatus;

new class extends Component
{
    #[Computed]
    public function features()
    {
        return Feature::query()
            ->whereNot('status', FeatureStatus::Cancelled)
            ->withCount(['votes', 'comments'])
            ->orderByDesc('votes_count')
            ->get();
    }
};
?>

<div>
    <section class="w-full max-w-4xl mx-auto">
        <x-header />

        <div class="space-y-4">
            @foreach ($this->features as $feature)
                <div class="p-4 border border-gray-500">
                    <h2 class="font-semibold text-lg">{{ $feature->name }}</h2>
                    <p class="text-sm text-gray-500">{{ $feature->description }}</p>
                </div>
            @endforeach
        </div>
    </section>
</div>
