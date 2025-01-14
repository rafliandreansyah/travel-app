<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

use function PHPSTORM_META\map;

class UsersChart extends ChartWidget
{
    protected static ?string $heading = 'Users Chart';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $data = Trend::model(User::class)
            ->between(
                start: now()->subMonths(11),
                end: now(),
            )
            ->perMonth()
            ->count();
        return [
            'datasets' => [
                [
                    'label' => 'User Created',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#9BD0F5',
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    public function getDescription(): ?string
    {
        return 'Number of user registrations per month in the last 1 year';
    }
}
