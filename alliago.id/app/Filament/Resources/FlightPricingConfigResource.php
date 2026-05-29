<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FlightPricingConfigResource\Pages;
use App\Models\FlightPricingConfig;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FlightPricingConfigResource extends Resource
{
    protected static ?string $model = FlightPricingConfig::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = 'Flight';

    protected static ?string $navigationLabel = 'Pricing Config';

    protected static ?string $modelLabel = 'Flight Pricing Config';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Flight Pricing')
                ->schema([
                    Forms\Components\TextInput::make('label')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('addon_cost')
                        ->label('Add-ons Cost (IDR)')
                        ->numeric()
                        ->required()
                        ->prefix('IDR')
                        ->helperText('Required fixed add-on cost added to every ticket price.'),
                    Forms\Components\TextInput::make('service_fee')
                        ->label('Service Fee (IDR)')
                        ->numeric()
                        ->required()
                        ->prefix('IDR')
                        ->helperText('Alliago service markup added on top of NET price.'),
                    Forms\Components\Textarea::make('notes')
                        ->rows(3)
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label'),
                Tables\Columns\TextColumn::make('addon_cost')
                    ->label('Add-ons Cost')
                    ->money('IDR', locale: 'id'),
                Tables\Columns\TextColumn::make('service_fee')
                    ->label('Service Fee')
                    ->money('IDR', locale: 'id'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFlightPricingConfigs::route('/'),
            'edit' => Pages\EditFlightPricingConfig::route('/{record}/edit'),
        ];
    }
}
