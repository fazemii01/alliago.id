<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class HelpModalWidget extends Widget
{
    protected static string $view = 'filament.widgets.help-modal';

    protected static ?int $sort = -99; // render first (above other widgets)

    protected int | string | array $columnSpan = 'full';
}
