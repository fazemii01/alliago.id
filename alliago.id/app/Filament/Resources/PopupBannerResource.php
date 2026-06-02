<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PopupBannerResource\Pages;
use App\Models\PopupBanner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PopupBannerResource extends Resource
{
    protected static ?string $model = PopupBanner::class;

    protected static ?string $navigationIcon = 'heroicon-o-window';

    protected static ?string $navigationGroup = 'Marketing';

    protected static ?string $navigationLabel = 'Popup Banners';

    protected static ?string $modelLabel = 'Popup Banner';

    protected static ?string $pluralModelLabel = 'Popup Banners';

    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user && ($user->hasRole('admin') || $user->hasPermissionTo('flight_pricing_config.view_any'));
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(3)
                ->schema([
                    Forms\Components\Section::make('General Configuration')
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('Campaign / Banner Name')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('e.g. Mid-Year Special Promo'),
                            Forms\Components\TextInput::make('delay_seconds')
                                ->label('Popup Delay (Seconds)')
                                ->numeric()
                                ->default(3)
                                ->required()
                                ->minValue(0)
                                ->maxValue(60),
                            Forms\Components\Toggle::make('is_active')
                                ->label('Activate Popup')
                                ->default(false),
                            Forms\Components\TextInput::make('redirect_link')
                                ->label('Redirect Link (On Click)')
                                ->url()
                                ->placeholder('e.g. https://www.alliago.id/promo-details')
                                ->maxLength(500),
                        ])
                        ->columnSpan(1),

                    Forms\Components\Grid::make(1)
                        ->schema([
                            Forms\Components\Section::make('Desktop Banner (Landscape)')
                                ->description('Horizontal aspect ratio suggested for desktop users.')
                                ->schema([
                                    Forms\Components\FileUpload::make('desktop_banner_path')
                                        ->label('Desktop Image File')
                                        ->image()
                                        ->disk('s3')
                                        ->directory('banners')
                                        ->columnSpanFull(),
                                    Forms\Components\TextInput::make('desktop_banner_url')
                                        ->label('External Desktop Image URL')
                                        ->url()
                                        ->maxLength(500)
                                        ->placeholder('https://example.com/desktop-banner.jpg')
                                        ->columnSpanFull(),
                                ])
                                ->collapsible(),

                            Forms\Components\Section::make('Mobile Banner (Portrait/Square)')
                                ->description('Vertical or square aspect ratio suggested for mobile screens.')
                                ->schema([
                                    Forms\Components\FileUpload::make('mobile_banner_path')
                                        ->label('Mobile Image File')
                                        ->image()
                                        ->disk('s3')
                                        ->directory('banners')
                                        ->columnSpanFull(),
                                    Forms\Components\TextInput::make('mobile_banner_url')
                                        ->label('External Mobile Image URL')
                                        ->url()
                                        ->maxLength(500)
                                        ->placeholder('https://example.com/mobile-banner.jpg')
                                        ->columnSpanFull(),
                                ])
                                ->collapsible(),
                        ])
                        ->columnSpan(2),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Campaign Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('desktop_preview')
                    ->label('Desktop Preview')
                    ->state(fn ($record) => $record->desktop_banner_path ? \Illuminate\Support\Facades\Storage::disk('s3')->url($record->desktop_banner_path) : $record->desktop_banner_url)
                    ->height(40)
                    ->width(120),
                Tables\Columns\ImageColumn::make('mobile_preview')
                    ->label('Mobile Preview')
                    ->state(fn ($record) => $record->mobile_banner_path ? \Illuminate\Support\Facades\Storage::disk('s3')->url($record->mobile_banner_path) : $record->mobile_banner_url)
                    ->height(40)
                    ->width(40),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('delay_seconds')
                    ->label('Delay')
                    ->suffix('s')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
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
            'index' => Pages\ManagePopupBanners::route('/'),
        ];
    }
}
