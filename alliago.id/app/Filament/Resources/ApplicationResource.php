<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplicationResource\Pages;
use App\Filament\Resources\ApplicationResource\RelationManagers\MessagesRelationManager;
use App\Models\Application;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ApplicationResource extends Resource
{
    protected static ?string $model = Application::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Operations';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Application')
                ->schema([
                    Forms\Components\TextInput::make('reference_number')->disabled(),
                    Forms\Components\Select::make('status')
                        ->options([
                            'draft' => 'Draft',
                            'documents_pending' => 'Documents pending',
                            'under_review' => 'Under review',
                            'needs_revision' => 'Needs revision',
                            'ready' => 'Ready',
                            'completed' => 'Completed',
                        ])
                        ->required(),
                    Forms\Components\TextInput::make('traveler_name')->required()->maxLength(255),
                    Forms\Components\TextInput::make('traveler_email')->email()->required()->maxLength(255),
                    Forms\Components\TextInput::make('traveler_phone')->maxLength(50),
                    Forms\Components\Textarea::make('notes')->rows(4)->columnSpanFull(),
                ])
                ->columns(2),
            Forms\Components\Section::make('Documents')
                ->schema([
                    Forms\Components\Repeater::make('documents')
                        ->relationship()
                        ->schema([
                            Forms\Components\TextInput::make('label')->disabled(),
                            Forms\Components\TextInput::make('file_path')->disabled(),
                            Forms\Components\Select::make('status')
                                ->options([
                                    'pending_upload' => 'Pending upload',
                                    'pending_review' => 'Pending review',
                                    'approved' => 'Approved',
                                    'needs_revision' => 'Needs revision',
                                    'optional' => 'Optional',
                                ])
                                ->required(),
                            Forms\Components\Textarea::make('admin_feedback')->rows(3),
                        ])
                        ->columns(2)
                        ->addable(false)
                        ->deletable(false)
                        ->reorderable(false),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('updated_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('reference_number')->searchable(),
                Tables\Columns\TextColumn::make('user.name')->label('User')->searchable(),
                Tables\Columns\TextColumn::make('visaProduct.name')->label('Visa product')->searchable(),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('unread_user_messages_count')
                    ->label('Unread')
                    ->badge()
                    ->color(fn (int $state): string => $state > 0 ? 'danger' : 'gray')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->since()->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withCount([
                'messages as unread_user_messages_count' => fn (Builder $query) => $query
                    ->where('is_admin', false)
                    ->whereNull('read_at'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApplications::route('/'),
            'edit' => Pages\EditApplication::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            MessagesRelationManager::class,
        ];
    }
}

