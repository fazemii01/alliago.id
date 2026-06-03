<?php

namespace App\Filament\Resources\ApplicationResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;

class MessagesRelationManager extends RelationManager
{
    protected static string $relationship = 'messages';

    protected static ?string $recordTitleAttribute = 'message';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Textarea::make('message')
                ->required()
                ->maxLength(2000)
                ->rows(4)
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\IconColumn::make('is_admin')
                    ->label('From admin')
                    ->boolean(),
                Tables\Columns\TextColumn::make('sender.name')
                    ->label('Sender')
                    ->placeholder('System'),
                Tables\Columns\TextColumn::make('message')
                    ->limit(80)
                    ->wrap(),
                Tables\Columns\IconColumn::make('read_at')
                    ->label('Read')
                    ->icon(fn ($state): string => filled($state) ? 'heroicon-o-check-circle' : 'heroicon-o-clock')
                    ->color(fn ($state): string => filled($state) ? 'success' : 'warning'),
                Tables\Columns\TextColumn::make('created_at')
                    ->since(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Reply to user')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['sender_id'] = auth()->id();
                        $data['is_admin'] = true;

                        return $data;
                    }),
                Action::make('markThreadRead')
                    ->label('Mark user messages read')
                    ->action(function (): void {
                        $count = $this->getOwnerRecord()
                            ->messages()
                            ->where('is_admin', false)
                            ->whereNull('read_at')
                            ->update(['read_at' => now()]);

                        Notification::make()
                            ->title("{$count} message(s) marked as read")
                            ->success()
                            ->send();
                    }),
            ])
            ->actions([])
            ->bulkActions([]);
    }
}

