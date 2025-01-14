<?php

namespace App\Filament\Widgets;

use App\Models\Car;
use App\Models\Company;
use App\Models\Transaction;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TravelResoucerOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Travel Joins', Company::all()->count())
                ->icon('heroicon-m-building-office')
                ->description('Total active travel')
                ->color('success'),
            Stat::make('Cars', Car::all()->count())
                ->icon('heroicon-m-rocket-launch')
                ->description('Total active cars')
                ->color('success'),
            Stat::make('Users', User::where('role', '=', 'user')->count())
                ->icon('heroicon-m-user-group')
                ->description('Total role user')
                ->color('success'),
            Stat::make('Transaction Count', Transaction::where('status_payment', '=', 'paid')->count())
                ->icon('heroicon-m-arrow-trending-up')
                ->description('Success transactions')
                ->color('success'),
        ];
    }
}
