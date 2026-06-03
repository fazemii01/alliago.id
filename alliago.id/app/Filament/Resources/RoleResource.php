<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use Database\Seeders\RolesAndPermissionsSeeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Roles & Permissions';

    public static function canAccess(): bool
    {
        return auth()->user() && auth()->user()->hasRole('admin');
    }

    public static function form(Form $form): Form
    {
        $groups = RolesAndPermissionsSeeder::$groups;
        $allPermissions = Permission::all()->pluck('id', 'name')->toArray();

        // Build grouped sections inside a Tabs component
        $tabs = [];
        foreach ($groups as $groupName => $permissions) {
            $icon = match ($groupName) {
                'Operations' => 'heroicon-o-clipboard-document-list',
                'Finance'    => 'heroicon-o-banknotes',
                'Catalog'    => 'heroicon-o-globe-alt',
                'Settings'   => 'heroicon-o-cog-6-tooth',
                default      => 'heroicon-o-squares-2x2',
            };

            // Map the permission IDs to their human-readable labels
            $tabOptions = [];
            foreach ($permissions as $name => $label) {
                if (isset($allPermissions[$name])) {
                    $tabOptions[$allPermissions[$name]] = $label;
                }
            }

            $tabs[] = Forms\Components\Tabs\Tab::make($groupName)
                ->icon($icon)
                ->schema([
                    Forms\Components\CheckboxList::make('permissions')
                        ->label('')
                        ->relationship('permissions', 'name')
                        ->options($tabOptions)
                        ->bulkToggleable()
                        ->columns(2)
                        ->gridDirection('row'),
                ]);
        }

        return $form
            ->schema([
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\Section::make('Role Details')
                            ->columnSpan(1)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255)
                                    ->helperText('e.g. admin, staff, finance-manager'),
                                Forms\Components\Placeholder::make('scope_note')
                                    ->label('ℹ️ Scope')
                                    ->content(
                                        'These permissions only control what this role can do inside the Admin Dashboard. ' .
                                        'They have no effect on public-facing pages like the landing page, visa listings, etc.'
                                    ),
                            ]),

                        Forms\Components\Section::make('Permissions')
                            ->columnSpan(2)
                            ->description('Select permissions by category. Use "Toggle all" within each tab to quickly grant or revoke an entire section.')
                            ->schema([
                                Forms\Components\Tabs::make('Permission Groups')
                                    ->tabs($tabs)
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        $groups = RolesAndPermissionsSeeder::$groups;

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'admin' => 'danger',
                        'staff' => 'warning',
                        default => 'info',
                    }),

                // Per-group permission coverage badges
                Tables\Columns\TextColumn::make('operations_coverage')
                    ->label('Operations')
                    ->getStateUsing(function (Role $record) use ($groups): string {
                        $perms = array_keys($groups['Operations']);
                        return $record->permissions->whereIn('name', $perms)->count() . '/' . count($perms);
                    })
                    ->badge()
                    ->color(fn (string $state) => self::badgeColor($state)),

                Tables\Columns\TextColumn::make('finance_coverage')
                    ->label('Finance')
                    ->getStateUsing(function (Role $record) use ($groups): string {
                        $perms = array_keys($groups['Finance']);
                        return $record->permissions->whereIn('name', $perms)->count() . '/' . count($perms);
                    })
                    ->badge()
                    ->color(fn (string $state) => self::badgeColor($state)),

                Tables\Columns\TextColumn::make('catalog_coverage')
                    ->label('Catalog')
                    ->getStateUsing(function (Role $record) use ($groups): string {
                        $perms = array_keys($groups['Catalog']);
                        return $record->permissions->whereIn('name', $perms)->count() . '/' . count($perms);
                    })
                    ->badge()
                    ->color(fn (string $state) => self::badgeColor($state)),

                Tables\Columns\TextColumn::make('settings_coverage')
                    ->label('Settings')
                    ->getStateUsing(function (Role $record) use ($groups): string {
                        $perms = array_keys($groups['Settings']);
                        return $record->permissions->whereIn('name', $perms)->count() . '/' . count($perms);
                    })
                    ->badge()
                    ->color(fn (string $state) => self::badgeColor($state)),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('has_access_to')
                    ->label('Has access to category')
                    ->options([
                        'Operations' => 'Operations',
                        'Finance'    => 'Finance',
                        'Catalog'    => 'Catalog',
                        'Settings'   => 'Settings',
                    ])
                    ->query(function ($query, array $data) use ($groups) {
                        $value = $data['value'] ?? null;
                        if (! $value || ! isset($groups[$value])) {
                            return $query;
                        }
                        $perms = array_keys($groups[$value]);
                        return $query->whereHas('permissions', fn ($q) => $q->whereIn('name', $perms));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->hidden(fn (Role $record) => $record->name === 'admin'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /** Colour a "X/Y" coverage badge. */
    private static function badgeColor(string $state): string
    {
        [$have, $total] = array_map('intval', explode('/', $state));
        if ($have === 0)      return 'gray';
        if ($have === $total) return 'success';
        return 'warning';
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit'   => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
