<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class HelpPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationLabel = 'Help & Guides';

    protected static ?string $title = 'Help & Guides';

    protected static ?string $navigationGroup = null; // top-level nav item

    protected static ?int $navigationSort = 99;

    protected static string $view = 'filament.pages.help-page';
}
