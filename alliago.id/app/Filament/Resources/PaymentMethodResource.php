<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentMethodResource\Pages;
use App\Filament\Resources\PaymentMethodResource\RelationManagers;
use App\Models\PaymentMethod;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentMethodResource extends Resource
{
    protected static ?string $model = PaymentMethod::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $modelLabel = 'Payment Method';
    protected static ?string $pluralModelLabel = 'Payment Methods';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Payment Method Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('provider')
                            ->options([
                                'manual' => 'Manual Transfer',
                                'xendit' => 'Xendit',
                            ])
                            ->required()
                            ->live(),
                        Forms\Components\TextInput::make('code')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\Toggle::make('is_active')
                            ->default(true)
                            ->required(),
                        Forms\Components\FileUpload::make('icon')
                            ->image()
                            ->directory('payment-methods')
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),
                    
                Forms\Components\Section::make('Manual Transfer Details')
                    ->schema([
                        Forms\Components\TextInput::make('account_name')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('account_number')
                            ->maxLength(255),
                    ])
                    ->columns(2)
                    ->description('Hanya diisi jika provider adalah Manual Transfer.')
                    ->visible(fn (Forms\Get $get) => $get('provider') === 'manual'),

                Forms\Components\Section::make('Xendit Settings')
                    ->schema([
                        Forms\Components\TextInput::make('configuration.secret_key')
                            ->label('Secret Key')
                            ->password()
                            ->revealable()
                            ->required(fn (Forms\Get $get) => $get('provider') === 'xendit'),
                        Forms\Components\TextInput::make('configuration.webhook_token')
                            ->label('Webhook Token')
                            ->password()
                            ->revealable()
                            ->required(fn (Forms\Get $get) => $get('provider') === 'xendit'),
                    ])
                    ->columns(2)
                    ->description('Konfigurasi API Key untuk Xendit.')
                    ->visible(fn (Forms\Get $get) => $get('provider') === 'xendit'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('icon')
                    ->square(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('provider')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'manual' => 'warning',
                        'xendit' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('code')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentMethods::route('/'),
            'create' => Pages\CreatePaymentMethod::route('/create'),
            'edit' => Pages\EditPaymentMethod::route('/{record}/edit'),
        ];
    }
}
