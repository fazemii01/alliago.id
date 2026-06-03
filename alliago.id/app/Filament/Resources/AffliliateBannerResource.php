<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AffliliateBannerResource\Pages;
use App\Models\FlightPricingConfig;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AffliliateBannerResource extends Resource
{
    protected static ?string $model = FlightPricingConfig::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?string $navigationGroup = 'Marketing';

    protected static ?string $navigationLabel = 'Affliliate banner';

    protected static ?string $modelLabel = 'Affliliate banner';

    protected static ?string $pluralModelLabel = 'Affliliate banners';

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

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        $user = auth()->user();
        return $user && ($user->hasRole('admin') || $user->hasPermissionTo('flight_pricing_config.delete'));
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Sidebar Banner / Affiliate Banner Configuration')
                ->description('Manage promotional portrait sidebar/affiliate banners displayed on the flight search page.')
                ->schema([
                    Forms\Components\TextInput::make('label')
                        ->label('Banner Identifier / Label')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                    Forms\Components\FileUpload::make('sidebar_banner_path')
                        ->label('Sidebar Banner Image (File Upload)')
                        ->image()
                        ->disk('s3')
                        ->directory('banners')
                        ->helperText('Upload an image file directly to MinIO. Suggested aspect ratio: 1:1 or vertical.')
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('sidebar_banner_image_url')
                        ->label('External Sidebar Banner Image URL')
                        ->url()
                        ->placeholder('e.g. https://example.com/sidebar-banner.jpg')
                        ->helperText('Alternatively, provide a direct URL to an externally hosted image.')
                        ->maxLength(500)
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('sidebar_banner_link')
                        ->label('Redirect Link (Optional)')
                        ->url()
                        ->placeholder('e.g. https://www.alliago.id/promo')
                        ->maxLength(500),
                    Forms\Components\Toggle::make('is_sidebar_banner_active')
                        ->label('Activate Sidebar Banner')
                        ->default(false),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->label('Banner Label')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('sidebar_banner_preview')
                    ->label('Banner Preview')
                    ->state(fn ($record) => $record->sidebar_banner_path ? \Illuminate\Support\Facades\Storage::disk('s3')->url($record->sidebar_banner_path) : $record->sidebar_banner_image_url)
                    ->height(100)
                    ->width(100),
                Tables\Columns\TextColumn::make('sidebar_banner_link')
                    ->label('Redirect Link')
                    ->placeholder('No redirection link set'),
                Tables\Columns\IconColumn::make('is_sidebar_banner_active')
                    ->label('Active Status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAffliliateBanners::route('/'),
            'edit' => Pages\EditAffliliateBanner::route('/{record}/edit'),
        ];
    }
}
