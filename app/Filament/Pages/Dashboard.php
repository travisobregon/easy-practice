<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Calendar';

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
}
