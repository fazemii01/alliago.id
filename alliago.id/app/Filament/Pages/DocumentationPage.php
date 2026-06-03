<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class DocumentationPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Documentation';

    protected static ?string $title = 'Documentation Center';

    protected static ?int $navigationSort = 100;

    protected static bool $shouldRegisterNavigation = false; // linked from HelpPage only

    protected static string $view = 'filament.pages.documentation-page';
}
