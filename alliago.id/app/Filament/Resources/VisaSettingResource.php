<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VisaSettingResource\Pages;
use App\Models\VisaSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VisaSettingResource extends Resource
{
    protected static ?string $model = VisaSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = 'Visa Catalog';

    protected static ?string $navigationLabel = 'Visa Currency';

    protected static ?string $modelLabel = 'Visa Currency';

    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user && ($user->hasRole('admin') || $user->can('visa_setting.view_any'));
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        $user = auth()->user();
        return $user && ($user->hasRole('admin') || $user->can('visa_setting.update'));
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Visa Pricing Currency Configuration')
                ->schema([
                    Forms\Components\Select::make('currency')
                        ->label('Visa Currency')
                        ->options([
                            'IDR' => 'IDR (Indonesian Rupiah)',
                            'MYR' => 'MYR (Malaysian Ringgit)',
                        ])
                        ->required()
                        ->helperText('Setting this currency will convert all visa prices in front-end and checkout to the selected currency.'),
                ])
                ->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('currency')
                    ->label('Current Currency')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'IDR' => 'success',
                        'MYR' => 'info',
                        default => 'gray',
                    }),
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
            'index' => Pages\ListVisaSettings::route('/'),
            'edit' => Pages\EditVisaSetting::route('/{record}/edit'),
        ];
    }
}
