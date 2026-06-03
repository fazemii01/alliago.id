<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FerryApplicationResource\Pages;
use App\Models\Application;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class FerryApplicationResource extends Resource
{
    protected static ?string $model = Application::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationLabel = 'Tiket Ferry';

    protected static ?string $navigationGroup = 'Operations';

    protected static ?string $modelLabel = 'Pesanan Ferry';

    protected static ?string $pluralModelLabel = 'Pesanan Ferry';

    protected static ?int  $navigationSort = 2;
    protected static bool  $shouldRegisterNavigation = false;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('reference_number', 'like', 'FRT-%');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Pesanan')
                ->schema([
                    Forms\Components\TextInput::make('reference_number')
                        ->label('No. Referensi')
                        ->disabled(),
                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'pending_payment'      => 'Menunggu Pembayaran',
                            'pending_verification' => 'Menunggu Verifikasi',
                            'payment_failed'       => 'Pembayaran Gagal',
                            'completed'            => 'Selesai',
                        ])
                        ->required(),
                    Forms\Components\TextInput::make('traveler_name')
                        ->label('Nama Penumpang')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('traveler_email')
                        ->label('Email')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('traveler_phone')
                        ->label('No. Telepon')
                        ->maxLength(50),
                    Forms\Components\Textarea::make('notes')
                        ->label('Catatan')
                        ->rows(3)
                        ->columnSpanFull(),
                ])
                ->columns(2),

            Forms\Components\Section::make('Detail Rute')
                ->schema([
                    Forms\Components\Placeholder::make('ferry_route')
                        ->label('Rute')
                        ->content(fn ($record) => $record
                            ? (($record->metadata['ferry_details']['origin'] ?? '-') . ' → ' . ($record->metadata['ferry_details']['destination'] ?? '-'))
                            : '-'),
                    Forms\Components\Placeholder::make('travel_date')
                        ->label('Tanggal Keberangkatan')
                        ->content(fn ($record) => $record && !empty($record->metadata['ferry_details']['travel_date'])
                            ? \Carbon\Carbon::parse($record->metadata['ferry_details']['travel_date'])->translatedFormat('d F Y')
                            : '-'),
                    Forms\Components\Placeholder::make('passenger_count')
                        ->label('Jumlah Penumpang')
                        ->content(fn ($record) => $record
                            ? ($record->metadata['ferry_details']['passenger_count'] ?? '-') . ' orang'
                            : '-'),
                    Forms\Components\Placeholder::make('invoice_amount')
                        ->label('Total Pembayaran')
                        ->content(fn ($record) => $record && isset($record->metadata['invoice_amount'])
                            ? 'Rp ' . number_format($record->metadata['invoice_amount'], 0, ',', '.')
                            : '-'),
                ])
                ->columns(2),

            Forms\Components\Section::make('Bukti Pembayaran')
                ->schema([
                    Forms\Components\Placeholder::make('payment_proof_preview')
                        ->label('Bukti Pembayaran')
                        ->content(function ($record) {
                            if (!$record || empty($record->metadata['payment_proof_path'])) {
                                return 'Belum ada bukti pembayaran.';
                            }

                            $url   = app(\App\Contracts\FileStorage::class)->url($record->metadata['payment_proof_path']);
                            $isPdf = str_ends_with(strtolower($record->metadata['payment_proof_path']), '.pdf');

                            if ($isPdf) {
                                return new \Illuminate\Support\HtmlString(
                                    '<a href="' . $url . '" target="_blank" class="inline-flex items-center gap-1 font-bold text-primary-600 hover:text-primary-500 underline">Lihat PDF &rarr;</a>'
                                );
                            }

                            return new \Illuminate\Support\HtmlString(
                                '<div>
                                    <div class="block border rounded-lg overflow-hidden max-w-xs">
                                        <img src="' . $url . '" alt="Bukti Pembayaran" class="w-full h-auto object-contain max-h-48" />
                                    </div>
                                </div>'
                            );
                        }),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reference_number')
                    ->label('No. Referensi')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('traveler_name')
                    ->label('Penumpang')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('traveler_email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('ferry_origin')
                    ->label('Asal')
                    ->getStateUsing(fn ($record) => $record->metadata['ferry_details']['origin'] ?? '-'),
                Tables\Columns\TextColumn::make('ferry_destination')
                    ->label('Tujuan')
                    ->getStateUsing(fn ($record) => $record->metadata['ferry_details']['destination'] ?? '-'),
                Tables\Columns\TextColumn::make('ferry_travel_date')
                    ->label('Tanggal')
                    ->getStateUsing(fn ($record) => !empty($record->metadata['ferry_details']['travel_date'])
                        ? \Carbon\Carbon::parse($record->metadata['ferry_details']['travel_date'])->format('d M Y')
                        : '-'),
                Tables\Columns\TextColumn::make('ferry_amount')
                    ->label('Total')
                    ->getStateUsing(fn ($record) => isset($record->metadata['invoice_amount'])
                        ? 'Rp ' . number_format($record->metadata['invoice_amount'], 0, ',', '.')
                        : '-'),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => ['pending_payment', 'pending_verification'],
                        'danger'  => 'payment_failed',
                        'success' => 'completed',
                    ])
                    ->formatStateUsing(fn ($state) => match($state) {
                        'pending_payment'      => 'Menunggu Bayar',
                        'pending_verification' => 'Verifikasi',
                        'payment_failed'       => 'Gagal',
                        'completed'            => 'Selesai',
                        default                => $state,
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending_payment'      => 'Menunggu Pembayaran',
                        'pending_verification' => 'Menunggu Verifikasi',
                        'payment_failed'       => 'Pembayaran Gagal',
                        'completed'            => 'Selesai',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('view_invoice')
                    ->label('Invoice')
                    ->icon('heroicon-o-document-text')
                    ->url(fn ($record) => route('ferry.invoice', $record))
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListFerryApplications::route('/'),
            'edit'  => Pages\EditFerryApplication::route('/{record}/edit'),
        ];
    }
}
