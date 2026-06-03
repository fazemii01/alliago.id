<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VisaProductResource\Pages;
use App\Models\Country;
use App\Models\VisaProduct;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VisaProductResource extends Resource
{
    protected static ?string $model = VisaProduct::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Visa Catalog';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Visa Product')
                ->schema([
                    Forms\Components\Select::make('country_id')
                        ->relationship('country', 'name', fn ($query) => $query->where('is_active', true))
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),
                    Forms\Components\TextInput::make('type')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\FileUpload::make('icon_image_path')
                        ->label('Icon Image')
                        ->image()
                        ->directory('visa-products/icons')
                        ->maxSize(5120),
                    Forms\Components\TextInput::make('promo_label')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('processing_time')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('stay_duration')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('validity')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('base_price')
                        ->numeric()
                        ->required()
                        ->prefix('IDR'),
                    Forms\Components\TextInput::make('discount_price')
                        ->numeric()
                        ->prefix('IDR'),
                    Forms\Components\Textarea::make('short_description')
                        ->rows(3),
                    Forms\Components\RichEditor::make('description')
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
            Forms\Components\Section::make('Requirements')
                ->schema([
                    Forms\Components\Repeater::make('requirements')
                        ->relationship()
                        ->schema([
                            Forms\Components\TextInput::make('title')->required()->maxLength(255),
                            Forms\Components\Textarea::make('description')->rows(2),
                            Forms\Components\TextInput::make('sort_order')->numeric()->default(0)->required(),
                        ])
                        ->defaultItems(0)
                        ->reorderableWithButtons()
                        ->collapsed(),
                ]),
            Forms\Components\Section::make('Documents')
                ->schema([
                    Forms\Components\Repeater::make('documents')
                        ->relationship()
                        ->schema([
                            Forms\Components\TextInput::make('name')->required()->maxLength(255),
                            Forms\Components\Textarea::make('description')->rows(2),
                            Forms\Components\Toggle::make('is_required')->default(true),
                            Forms\Components\TextInput::make('sort_order')->numeric()->default(0)->required(),
                        ])
                        ->defaultItems(0)
                        ->reorderableWithButtons()
                        ->collapsed(),
                ]),
            Forms\Components\Section::make('Process Steps')
                ->schema([
                    Forms\Components\Repeater::make('processSteps')
                        ->relationship()
                        ->schema([
                            Forms\Components\TextInput::make('title')->required()->maxLength(255),
                            Forms\Components\Textarea::make('description')->rows(2),
                            Forms\Components\TextInput::make('sort_order')->numeric()->default(0)->required(),
                        ])
                        ->defaultItems(0)
                        ->reorderableWithButtons()
                        ->collapsed(),
                ]),
            Forms\Components\Section::make('FAQs')
                ->schema([
                    Forms\Components\Repeater::make('faqs')
                        ->relationship()
                        ->schema([
                            Forms\Components\TextInput::make('question')->required()->maxLength(255),
                            Forms\Components\Textarea::make('answer')->required()->rows(3),
                            Forms\Components\TextInput::make('sort_order')->numeric()->default(0)->required(),
                        ])
                        ->defaultItems(0)
                        ->reorderableWithButtons()
                        ->collapsed(),
                ]),
            Forms\Components\Section::make('Add-ons')
                ->schema([
                    Forms\Components\Repeater::make('addons')
                        ->relationship()
                        ->schema([
                            Forms\Components\TextInput::make('name')->required()->maxLength(255),
                            Forms\Components\Textarea::make('description')->rows(2),
                            Forms\Components\TextInput::make('price')->numeric()->required()->prefix('IDR'),
                            Forms\Components\Toggle::make('is_active')->default(true),
                            Forms\Components\TextInput::make('sort_order')->numeric()->default(0)->required(),
                        ])
                        ->defaultItems(0)
                        ->reorderableWithButtons()
                        ->collapsed(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                Tables\Columns\ImageColumn::make('icon_image_path')
                    ->label('Icon')
                    ->square(),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('country.name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('type')->badge(),
                Tables\Columns\TextColumn::make('base_price')->money('IDR', divideBy: 1)->sortable(),
                Tables\Columns\TextColumn::make('discount_price')->money('IDR', divideBy: 1)->sortable(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->since()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('country_id')
                    ->label('Country')
                    ->options(fn () => Country::query()->orderBy('name')->pluck('name', 'id')->all()),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVisaProducts::route('/'),
            'create' => Pages\CreateVisaProduct::route('/create'),
            'edit' => Pages\EditVisaProduct::route('/{record}/edit'),
        ];
    }
}
