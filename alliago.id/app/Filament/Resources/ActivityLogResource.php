<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use App\Models\ActivityLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Activity Logs';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('General Audit Details')
                ->schema([
                    Forms\Components\Grid::make(3)
                        ->schema([
                            Forms\Components\TextInput::make('created_at')
                                ->label('Timestamp')
                                ->disabled(),
                            Forms\Components\TextInput::make('user.name')
                                ->label('Executed By')
                                ->placeholder('System / Anonymous')
                                ->disabled(),
                            Forms\Components\TextInput::make('ip_address')
                                ->label('IP Address')
                                ->disabled(),
                        ]),
                    Forms\Components\Grid::make(3)
                        ->schema([
                            Forms\Components\TextInput::make('action')
                                ->label('Action Type')
                                ->disabled(),
                            Forms\Components\TextInput::make('model_type')
                                ->label('Model Class')
                                ->formatStateUsing(fn ($state) => $state ? class_basename($state) : '-')
                                ->disabled(),
                            Forms\Components\TextInput::make('model_id')
                                ->label('Record ID')
                                ->disabled(),
                        ]),
                    Forms\Components\TextInput::make('description')
                        ->label('Summary')
                        ->disabled()
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('user_agent')
                        ->label('User Agent')
                        ->disabled()
                        ->rows(2)
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Audit Trail Diffs')
                ->schema([
                    Forms\Components\Placeholder::make('properties')
                        ->label('')
                        ->content(function ($record) {
                            if (empty($record?->properties)) {
                                return 'No attribute changes recorded.';
                            }

                            $old = $record->properties['old'] ?? null;
                            $new = $record->properties['new'] ?? null;

                            $html = '<div class="space-y-4">';
                            
                            if ($old && !$new) {
                                // Deleted model
                                $html .= '<h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400">Deleted Data:</h4>';
                                $html .= '<pre class="p-4 bg-gray-50 dark:bg-gray-900 rounded-lg overflow-x-auto text-xs text-red-600 dark:text-red-400 font-mono">' . e(json_encode($old, JSON_PRETTY_PRINT)) . '</pre>';
                            } elseif ($new && $old) {
                                // Updated model: show side-by-side or line-by-line comparison
                                $html .= '<h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400">Modified Attributes:</h4>';
                                $html .= '<div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">';
                                $html .= '<table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-xs font-mono">';
                                $html .= '<thead class="bg-gray-50 dark:bg-gray-800">';
                                $html .= '<tr>';
                                $html .= '<th class="px-4 py-2 text-left font-medium text-gray-500 dark:text-gray-400">Field</th>';
                                $html .= '<th class="px-4 py-2 text-left font-medium text-red-500 dark:text-red-400">Original</th>';
                                $html .= '<th class="px-4 py-2 text-left font-medium text-green-500 dark:text-green-400">New Value</th>';
                                $html .= '</tr>';
                                $html .= '</thead>';
                                $html .= '<tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">';
                                foreach ($new as $key => $val) {
                                    $oldVal = $old[$key] ?? '';
                                    
                                    $oldStr = is_array($oldVal) ? json_encode($oldVal) : (string)$oldVal;
                                    $newStr = is_array($val) ? json_encode($val) : (string)$val;

                                    $html .= '<tr>';
                                    $html .= '<td class="px-4 py-2 font-semibold text-gray-700 dark:text-gray-300">' . e($key) . '</td>';
                                    $html .= '<td class="px-4 py-2 text-red-600 dark:text-red-400 bg-red-50/50 dark:bg-red-950/20">' . e($oldStr) . '</td>';
                                    $html .= '<td class="px-4 py-2 text-green-600 dark:text-green-400 bg-green-50/50 dark:bg-green-950/20">' . e($newStr) . '</td>';
                                    $html .= '</tr>';
                                }
                                $html .= '</tbody>';
                                $html .= '</table>';
                                $html .= '</div>';
                            } elseif ($new) {
                                // Created model
                                $html .= '<h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400">New Data Attributes:</h4>';
                                $html .= '<pre class="p-4 bg-gray-50 dark:bg-gray-900 rounded-lg overflow-x-auto text-xs text-green-600 dark:text-green-400 font-mono">' . e(json_encode($new, JSON_PRETTY_PRINT)) . '</pre>';
                            } else {
                                // Standard raw properties
                                $html .= '<pre class="p-4 bg-gray-50 dark:bg-gray-900 rounded-lg overflow-x-auto text-xs font-mono">' . e(json_encode($record->properties, JSON_PRETTY_PRINT)) . '</pre>';
                            }

                            $html .= '</div>';
                            return new \Illuminate\Support\HtmlString($html);
                        })
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Timestamp')
                    ->dateTime('d M Y H:i:s')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Admin/Staff')
                    ->placeholder('System')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('action')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'create' => 'success',
                        'update' => 'warning',
                        'delete' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'create' => 'heroicon-m-plus-circle',
                        'update' => 'heroicon-m-pencil-square',
                        'delete' => 'heroicon-m-trash',
                        default => 'heroicon-m-information-circle',
                    })
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('model_type')
                    ->label('Model')
                    ->formatStateUsing(fn ($state) => $state ? class_basename($state) : '-')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('model_id')
                    ->label('Record ID')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('action')
                    ->options([
                        'create' => 'Create',
                        'update' => 'Update',
                        'delete' => 'Delete',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->slideOver(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
        ];
    }
}
