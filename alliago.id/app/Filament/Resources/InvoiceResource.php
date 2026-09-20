<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\Widgets\InvoiceOverview;
use App\Models\Application;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class InvoiceResource extends Resource
{
    protected static ?string $model = Application::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Finance';
    
    protected static ?string $modelLabel = 'Invoice';
    
    protected static ?string $pluralModelLabel = 'Invoices';

    public static function form(Form $form): Form
    {
        return $form->schema([
            // Since this is just for viewing, we can leave the form empty or use a placeholder
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('reference_number')
                    ->label('Invoice ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable(),
                Tables\Columns\TextColumn::make('traveler_name')
                    ->label('Customer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('visaProduct.name')
                    ->label('Description')
                    ->searchable()
                    ->default(fn ($record) => $record->metadata && ($record->metadata['type'] ?? null) === 'flight' 
                        ? 'Flight: ' . ($record->metadata['flight_details']['airline_name'] ?? 'Penerbangan')
                        : '-'
                    ),
                Tables\Columns\TextColumn::make('currency')
                    ->label('Currency')
                    ->getStateUsing(function (Application $record) {
                        $curr = $record->metadata['price_breakdown']['currency'] ?? $record->metadata['currency'] ?? null;
                        if (!$curr && ($record->metadata['type'] ?? '') === 'ferry') {
                            return 'RM';
                        }
                        return in_array(strtoupper($curr ?? ''), ['MYR', 'RM']) ? 'RM' : 'IDR';
                    })
                    ->badge()
                    ->color(fn (string $state): string => $state === 'RM' ? 'info' : 'success'),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->getStateUsing(function (Application $record) {
                        $curr = $record->metadata['price_breakdown']['currency'] ?? $record->metadata['currency'] ?? null;
                        $isRm = in_array(strtoupper($curr ?? ''), ['MYR', 'RM']) || (($record->metadata['type'] ?? '') === 'ferry');
                        $symbol = $isRm ? 'RM ' : 'Rp ';
                        $amount = $record->metadata['invoice_amount'] 
                            ?? $record->metadata['price_breakdown']['total'] 
                            ?? ($record->visaProduct?->discount_price ?? $record->visaProduct?->base_price ?? 0);
                        return $symbol . number_format($amount, 0, ',', '.');
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => $state === 'pending_payment' ? 'Unpaid' : 'Paid')
                    ->color(fn (?string $state) => $state === 'pending_payment' ? 'warning' : 'success'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('currency')
                    ->label('Currency')
                    ->options([
                        'IDR' => 'IDR (Rp)',
                        'RM' => 'RM (Ringgit)',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (empty($data['value'])) {
                            return $query;
                        }
                        if ($data['value'] === 'RM') {
                            return $query->where(function (Builder $q) {
                                $q->where('metadata->price_breakdown->currency', 'MYR')
                                  ->orWhere('metadata->price_breakdown->currency', 'RM')
                                  ->orWhere('metadata->currency', 'MYR')
                                  ->orWhere('metadata->currency', 'RM')
                                  ->orWhere('metadata->type', 'ferry');
                            });
                        }
                        return $query->where(function (Builder $q) {
                            $q->where('metadata->price_breakdown->currency', 'IDR')
                              ->orWhere('metadata->currency', 'IDR')
                              ->orWhere(function ($sub) {
                                  $sub->whereNull('metadata->price_breakdown->currency')
                                      ->whereNull('metadata->currency')
                                      ->where(function ($sub2) {
                                          $sub2->whereNull('metadata->type')
                                               ->orWhere('metadata->type', '!=', 'ferry');
                                      });
                              });
                        });
                    }),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')->label('From Date'),
                        Forms\Components\DatePicker::make('created_until')->label('Until Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\Action::make('change_currency')
                    ->label('Opsi Currency')
                    ->icon('heroicon-o-currency-dollar')
                    ->color('gray')
                    ->form([
                        Forms\Components\Select::make('currency')
                            ->label('Tipe Mata Uang (Currency)')
                            ->options([
                                'IDR' => 'IDR (Indonesian Rupiah - Rp)',
                                'MYR' => 'RM (Malaysian Ringgit - RM)',
                            ])
                            ->required()
                            ->default(fn (Application $record) => in_array(strtoupper($record->metadata['price_breakdown']['currency'] ?? $record->metadata['currency'] ?? ''), ['MYR', 'RM']) ? 'MYR' : 'IDR'),
                        Forms\Components\TextInput::make('invoice_amount')
                            ->label('Nominal Tagihan Baru')
                            ->numeric()
                            ->required()
                            ->default(fn (Application $record) => $record->metadata['invoice_amount'] ?? $record->metadata['price_breakdown']['total'] ?? ($record->visaProduct?->discount_price ?? $record->visaProduct?->base_price ?? 0))
                            ->helperText('Perbarui nominal tagihan jika dikonversi ke mata uang baru.'),
                    ])
                    ->action(function (Application $record, array $data): void {
                        $metadata = $record->metadata ?? [];
                        $newCurrency = $data['currency'] === 'RM' ? 'MYR' : $data['currency'];
                        $newAmount = (float) $data['invoice_amount'];
                        
                        $metadata['currency'] = $newCurrency;
                        if (!isset($metadata['price_breakdown'])) {
                            $metadata['price_breakdown'] = [];
                        }
                        $metadata['price_breakdown']['currency'] = $newCurrency;
                        $metadata['price_breakdown']['total'] = $newAmount;
                        $metadata['invoice_amount'] = $newAmount;
                        
                        $record->metadata = $metadata;
                        $record->save();
                    }),
                Tables\Actions\Action::make('view_invoice')
                    ->label('View Invoice')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Application $record): string => route('client.applications.invoice', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageInvoices::route('/'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            InvoiceOverview::class,
        ];
    }
}
