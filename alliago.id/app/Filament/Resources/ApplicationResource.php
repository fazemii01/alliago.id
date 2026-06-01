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
                            'pending_payment' => 'Pending payment',
                            'pending_verification' => 'Pending verification',
                            'payment_failed' => 'Payment failed',
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
            Forms\Components\Section::make('Payment Verification')
                ->description('Review manual payment proof and update payment status.')
                ->schema([
                    Forms\Components\Placeholder::make('payment_proof_preview')
                        ->label('Proof of Payment')
                        ->content(function ($record) {
                            if (!$record || empty($record->metadata['payment_proof_path'])) {
                                return 'No payment proof uploaded.';
                            }
                            
                            $url = app(\App\Contracts\FileStorage::class)->url($record->metadata['payment_proof_path']);
                            $isPdf = str_ends_with(strtolower($record->metadata['payment_proof_path']), '.pdf');
                            
                            if ($isPdf) {
                                return new \Illuminate\Support\HtmlString('
                                    <div class="flex flex-col gap-2">
                                        <span class="text-sm text-slate-500">Uploaded PDF Document:</span>
                                        <a href="' . $url . '" target="_blank" class="inline-flex items-center gap-1 font-bold text-primary-600 hover:text-primary-500 underline">
                                            View PDF Proof &rarr;
                                        </a>
                                    </div>
                                ');
                            }
                            
                            return new \Illuminate\Support\HtmlString('
                                <div x-data="{ open: false }">
                                    <!-- Trigger Image -->
                                    <div class="space-y-2">
                                        <div class="block border rounded-lg overflow-hidden max-w-xs hover:opacity-90 transition cursor-pointer" @click="open = true">
                                            <img src="' . $url . '" alt="Payment Proof" class="w-full h-auto object-contain max-h-48" />
                                        </div>
                                        <span class="text-xs text-slate-500 font-medium">Click image to enlarge/preview</span>
                                    </div>

                                    <!-- Alpine Modal -->
                                    <div x-show="open" 
                                         x-transition.opacity 
                                         class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
                                         @click.away="open = false"
                                         @keydown.escape.window="open = false"
                                         style="display: none;">
                                        
                                        <div class="relative bg-white dark:bg-slate-800 p-3 rounded-2xl max-w-4xl max-h-[90vh] shadow-2xl overflow-hidden flex flex-col items-center border border-slate-200 dark:border-slate-700" 
                                             @click.stop>
                                            
                                            <!-- Close Button -->
                                            <button @click="open = false" 
                                                    type="button"
                                                    class="absolute top-4 right-4 text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 rounded-full p-2 transition shadow">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                            
                                            <!-- Modal Content -->
                                            <div class="overflow-auto mt-10 max-h-[80vh]">
                                                <img src="' . $url . '" alt="Payment Proof Large" class="max-w-full h-auto object-contain max-h-[75vh] rounded-lg" />
                                            </div>
                                            
                                            <div class="mt-4 flex justify-between w-full items-center px-2">
                                                <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">Proof of Payment</span>
                                                <a href="' . $url . '" target="_blank" class="inline-flex items-center gap-1 text-xs font-bold text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 underline">
                                                    Open in new tab &rarr;
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ');
                        }),
                    Forms\Components\Select::make('metadata.payment_status')
                        ->label('Payment Status')
                        ->options([
                            'unpaid' => 'Unpaid',
                            'pending_verification' => 'Pending Verification',
                            'paid' => 'Paid',
                            'declined' => 'Declined / Failed',
                        ])
                        ->default('unpaid')
                        ->required(),
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
                                        ? new \Illuminate\Support\HtmlString('<a href="'.app(\App\Contracts\FileStorage::class)->url($record->file_path).'" target="_blank" class="font-bold text-primary-600 hover:text-primary-500 underline">View Document &rarr;</a>') 
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
                ])
                ->visible(fn ($record) => $record && $record->visa_product_id !== null),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('updated_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('reference_number')->searchable(),
                Tables\Columns\TextColumn::make('user.name')->label('User')->searchable(),
                Tables\Columns\TextColumn::make('visaProduct.name')
                    ->label('Product / Service')
                    ->searchable()
                    ->default(fn ($record) => $record->metadata && ($record->metadata['type'] ?? null) === 'flight' 
                        ? 'Flight: ' . ($record->metadata['flight_details']['airline_name'] ?? 'Penerbangan')
                        : '-'
                    ),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('metadata.payment_status')
                    ->label('Payment Status')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'paid' => 'success',
                        'pending_verification' => 'warning',
                        'declined' => 'danger',
                        default => 'gray',
                    })
                    ->default('unpaid'),
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
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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

