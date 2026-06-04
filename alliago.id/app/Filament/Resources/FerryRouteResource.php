<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FerryRouteResource\Pages;
use App\Models\FerryRoute;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FerryRouteResource extends Resource
{
    protected static ?string $model = FerryRoute::class;

    protected static ?string $navigationIcon   = 'heroicon-o-map';
    protected static ?string $navigationLabel  = 'Rute Ferry';
    protected static ?string $navigationGroup  = 'Operations';
    protected static ?string $modelLabel       = 'Rute Ferry';
    protected static ?string $pluralModelLabel = 'Rute Ferry';
    protected static ?int    $navigationSort   = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Rute')
                ->schema([
                    Forms\Components\TextInput::make('origin')
                        ->label('Pelabuhan Asal')
                        ->required()
                        ->maxLength(100),
                    Forms\Components\TextInput::make('destination')
                        ->label('Pelabuhan Tujuan')
                        ->required()
                        ->maxLength(100),
                    Forms\Components\TextInput::make('price')
                        ->label('Harga (Rp)')
                        ->numeric()
                        ->required()
                        ->minValue(0)
                        ->suffix('IDR'),
                    Forms\Components\FileUpload::make('ship_image_path')
                        ->label('Foto Kapal (Ship Image)')
                        ->image()
                        ->directory('ferry-routes/ships')
                        ->maxSize(5120)
                        ->nullable(),
                    Forms\Components\Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('origin')
                    ->label('Asal')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('destination')
                    ->label('Tujuan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),
                Tables\Columns\ImageColumn::make('ship_image_path')
                    ->label('Foto Kapal')
                    ->circular(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('id')
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
            'index'  => Pages\ListFerryRoutes::route('/'),
            'create' => Pages\CreateFerryRoute::route('/create'),
            'edit'   => Pages\EditFerryRoute::route('/{record}/edit'),
        ];
    }
}
