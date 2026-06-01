<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FlightBannerResource\Pages;
use App\Models\FlightPricingConfig;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FlightBannerResource extends Resource
{
    protected static ?string $model = FlightPricingConfig::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationGroup = 'Marketing';

    protected static ?string $navigationLabel = 'Flight Banners';

    protected static ?string $modelLabel = 'Flight Banner';

    protected static ?string $pluralModelLabel = 'Flight Banners';

    public static function canAccess(): bool
    {
        $user = auth()->user();
        return $user && ($user->hasRole('admin') || $user->hasPermissionTo('flight_pricing_config.view_any'));
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        $user = auth()->user();
        return $user && ($user->hasRole('admin') || $user->hasPermissionTo('flight_pricing_config.update'));
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Promo Banner Configuration')
                ->description('Manage the promotional banner displayed above the flight search results.')
                ->schema([
                    Forms\Components\FileUpload::make('banner_path')
                        ->label('Banner Image (File Upload)')
                        ->image()
                        ->disk('s3') // Explicitly store uploads directly to MinIO
                        ->directory('banners')
                        ->helperText('Upload an image file directly to MinIO. Suggested aspect ratio: 810px x 195.5px.')
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('banner_image_url')
                        ->label('External Banner Image URL')
                        ->url()
                        ->placeholder('e.g. https://example.com/promo-banner.jpg')
                        ->helperText('Alternatively, provide a direct URL to an externally hosted image.')
                        ->maxLength(500)
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('banner_link')
                        ->label('Redirect Link (Optional)')
                        ->url()
                        ->placeholder('e.g. https://www.alliago.id/promo')
                        ->maxLength(500),
                    Forms\Components\Toggle::make('is_banner_active')
                        ->label('Activate Banner')
                        ->default(false),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('banner_preview')
                    ->label('Banner Preview')
                    ->state(fn ($record) => $record->banner_path ? \Illuminate\Support\Facades\Storage::disk('s3')->url($record->banner_path) : $record->banner_image_url)
                    ->height(60)
                    ->width(248),
                Tables\Columns\TextColumn::make('banner_link')
                    ->label('Redirect Link')
                    ->placeholder('No redirection link set'),
                Tables\Columns\IconColumn::make('is_banner_active')
                    ->label('Active Status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
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
            'index' => Pages\ListFlightBanners::route('/'),
            'edit' => Pages\EditFlightBanner::route('/{record}/edit'),
        ];
    }
}
