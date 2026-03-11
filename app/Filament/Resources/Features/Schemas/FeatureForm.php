<?php

namespace App\Filament\Resources\Features\Schemas;

use App\Enums\Feature\FeatureStatus;
use App\Enums\Feature\FeatureType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Slider;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rule;

class FeatureForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                TextInput::make('name')
                    ->required(),
                Select::make('status')
                    ->options(FeatureStatus::class)
                    ->enum(FeatureStatus::class)
                    ->searchable()
                    ->required()
                    ->default(FeatureStatus::Proposed),
                DatePicker::make('target_delivery_date')
                    ->rules([
                        function (Get $get) {
                            return Rule::requiredIf($get('status') === FeatureStatus::Planned || $get('status') === FeatureStatus::InProgress);
                        },
                    ])
                    ->required()
                    ->visibleJs(<<<'JS'
                        $get('status') === 'Planned' || $get('status') === 'In Progress'
                    JS
                    ),
                ToggleButtons::make('type')
                    ->hiddenLabel()
                    ->options(FeatureType::class)
                    ->enum(FeatureType::class)
                    ->inline()
                    ->required()
                    ->default(FeatureType::Feature),
                RichEditor::make('description')
                    ->columnSpanFull()
                    ->required(),
                TextInput::make('effort_in_days')
                    ->required()
                    ->numeric()
                    ->default(0),
                Slider::make('priority')
                    ->minValue(1)
                    ->maxValue(10)
                    ->pips(Slider\Enums\PipsMode::Steps)
                    ->step(1)
                    ->fillTrack()
                    ->required()
                    ->default(0),
                TextInput::make('cost')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('$'),
                DateTimePicker::make('delivered_at'),
            ]);
    }
}
