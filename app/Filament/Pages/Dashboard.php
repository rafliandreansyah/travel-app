<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Pages\Dashboard as PagesDashboard;
use Filament\Pages\Dashboard\Actions\FilterAction;
use Filament\Widgets\StatsOverviewWidget;

class Dashboard extends PagesDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static string $view = 'filament.pages.dashboard';

    protected ?string $heading = 'Travel Stats Overview';

    protected function getHeaderActions(): array
    {
        return [
            FilterAction::make()
                ->form([
                    DatePicker::make('startDate'),
                    DatePicker::make('endDate'),
                    // ...
                ]),
        ];
    }
}
