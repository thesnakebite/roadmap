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
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Tabs;
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
                Tabs::make('Feature Tabs')
                    ->columnSpanFull()
                    ->tabs([
                        Tabs\Tab::make('General')
                            ->columns(3)
                            ->schema(self::getGeneralTabSchema()),

                        Tabs\Tab::make('Effort and Cost')
                            ->columns(2)
                            ->schema(self::getEffortAndCostTabSchema()),
                    ]),
            ]);
    }

    private static function getGeneralTabSchema(): array
    {
        return [
            TextInput::make('name')
                ->required(),
            Select::make('status')
                ->options(FeatureStatus::class)
                ->enum(FeatureStatus::class)
                ->searchable()
                ->required()
                ->default(FeatureStatus::Proposed),
            Slider::make('priority')
                ->extraFieldWrapperAttributes([
                    'class' => 'pl-3',
                ])
                ->minValue(1)
                ->maxValue(10)
                ->pips(Slider\Enums\PipsMode::Steps)
                ->step(1)
                ->fillTrack()
                ->required()
                ->default(0),
            ToggleButtons::make('type')
                ->hiddenLabel()
                ->options(FeatureType::class)
                ->enum(FeatureType::class)
                ->inline()
                ->required()
                ->default(FeatureType::Feature),
            DatePicker::make('target_delivery_date')
                ->rules([
                    function (Get $get) {
                        return Rule::requiredIf($get('status') === FeatureStatus::Planned || $get('status') === FeatureStatus::InProgress);
                    },
                ])
                ->required()
                ->visibleJs(
                    <<<'JS'
                        $get('status') === 'Planned' || $get('status') === 'In Progress'
                    JS
                ),
            DateTimePicker::make('delivered_at')
                ->visibleJs(
                    <<<'JS'
                    $get('status') === 'Completed'
                JS
                ),
            RichEditor::make('description')
                ->extraInputAttributes([
                    'style' => 'min-height: 150px;',
                ])
                ->columnSpanFull()
                ->required(),
        ];
    }

    private static function getEffortAndCostTabSchema(): array
    {
        return [
            TextInput::make('effort_in_days')
                ->required()
                ->numeric()
                ->afterStateUpdatedJs(<<<'JS'
                            const isHighCost = $get('is_high_cost');
                            const effort = $state;
                            const costPerDay = isHighCost ? 1500 : 1000;
                            $set('cost', effort * costPerDay);
                        JS
                )
                ->default(0),
            TextInput::make('cost')
                ->required()
                ->numeric()
                ->default(0.0)
                ->prefix('$'),
            Toggle::make('is_high_cost')
                ->label('Is High Cost')
                ->dehydrated(false)
                ->afterStateUpdatedJs(
                    <<<'JS'
                        const isHighCost = $state;
                        const effort = $get('effort_in_days');
                        const costPerDay = isHighCost ? 1500 : 1000;
                        $set('cost', effort * costPerDay);
                    JS
                ),
        ];
    }
}
