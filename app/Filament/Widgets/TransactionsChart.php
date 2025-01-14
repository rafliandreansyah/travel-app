<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use App\Models\User;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class TransactionsChart extends ChartWidget
{
    protected static ?string $heading = 'Transactions Chart';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = Trend::query(Transaction::where('status_payment', '=', 'paid'))
            ->between(
                start: now()->subMonths(11),
                end: now(),
            )
            ->perMonth()
            ->count();
        return [
            'datasets' => [
                [
                    'label' => 'Car Rented',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                    'backgroundColor' => '#54C392',
                    'borderColor' => '#54C392',
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    public function getDescription(): ?string
    {
        return 'Number of successful transactions per month in the last 1 year';
    }
}
