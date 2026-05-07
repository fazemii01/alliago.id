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
                            Forms\Components\Grid::make(3)->schema([
                                Forms\Components\TextInput::make('label')
                                    ->disabled()
                                    ->columnSpan(1),
                                Forms\Components\Placeholder::make('file')
                                    ->label('Uploaded Document')
                                    ->content(fn ($record) => $record && $record->file_path 
                                        ? new \Illuminate\Support\HtmlString('<a href="'.\Illuminate\Support\Facades\Storage::url($record->file_path).'" target="_blank" class="font-bold text-primary-600 hover:text-primary-500 underline">View Document &rarr;</a>') 
                                        : 'No file uploaded')
                                    ->columnSpan(1),
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'pending_upload' => 'Menunggu Upload',
                                        'pending_review' => 'Menunggu Review Admin',
                                        'approved' => 'Dokumen Valid (Approved)',
                                        'declined' => 'Ditolak (Declined)',
                                        'needs_revision' => 'Perlu Revisi',
                                        'optional' => 'Opsional',
                                    ])
                                    ->required()
                                    ->columnSpan(1),
                            ]),
                            Forms\Components\Textarea::make('admin_feedback')
                                ->label('Feedback / Notes for Client')
                                ->rows(3)
                                ->columnSpanFull(),
                        ])
                        ->columnSpanFull()
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
                Tables\Actions\EditAction::make()
                    ->label('Process')
                    ->icon('heroicon-o-cog-8-tooth')
                    ->color('primary')
                    ->button(),
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

