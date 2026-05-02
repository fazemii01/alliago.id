<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteFaqResource\Pages;
use App\Models\SiteFaq;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SiteFaqResource extends Resource
{
    protected static ?string $model = SiteFaq::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationGroup = 'Landing Page';

    protected static ?string $navigationLabel = 'Site FAQs';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('FAQ Entry')
                ->schema([
                    Forms\Components\TextInput::make('question')
                        ->required()
                        ->maxLength(500)
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('answer')
                        ->required()
                        ->rows(4)
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('sort_order')
                        ->numeric()
                        ->default(0)
                        ->required(),
                    Forms\Components\Toggle::make('is_active')
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
                Tables\Columns\TextColumn::make('question')->searchable()->sortable()->limit(60),
                Tables\Columns\TextColumn::make('answer')->limit(80)->wrap(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
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
            'index' => Pages\ListSiteFaqs::route('/'),
            'create' => Pages\CreateSiteFaq::route('/create'),
            'edit' => Pages\EditSiteFaq::route('/{record}/edit'),
        ];
    }
}
