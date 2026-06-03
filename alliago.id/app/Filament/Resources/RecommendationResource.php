<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RecommendationResource\Pages;
use App\Models\Recommendation;
use App\Models\VisaProduct;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RecommendationResource extends Resource
{
    protected static ?string $model = Recommendation::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $navigationGroup = 'Flight';

    protected static ?string $navigationLabel = 'Recomendations';

    protected static ?string $modelLabel = 'Recommendation';

    protected static ?string $pluralModelLabel = 'Recomendations';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Flight Visa Recommendation')
                ->schema([
                    Forms\Components\Select::make('visa_product_id')
                        ->label('Visa Product')
                        ->relationship('visaProduct', 'name')
                        ->getOptionLabelFromRecordUsing(fn ($record) => $record->name . ' (' . ($record->country?->name ?? 'No Country') . ' - ' . $record->type . ')')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\TextInput::make('sort_order')
                        ->numeric()
                        ->default(0)
                        ->required(),
                    Forms\Components\Toggle::make('is_active')
                        ->label('Active')
                        ->default(true)
                        ->required(),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('visaProduct.name')
                    ->label('Visa Product')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('visaProduct.country.name')
                    ->label('Country')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRecommendations::route('/'),
            'create' => Pages\CreateRecommendation::route('/create'),
            'edit' => Pages\EditRecommendation::route('/{record}/edit'),
        ];
    }
}
