<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Str;
use App\Models\Transaction;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class WaitingApproveTransactions extends BaseWidget
{
    protected static ?string $model = Transaction::class;
    protected int | string | array $columnSpan = 2;
    protected static ?int $sort = 4;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transaction::where('status_payment', '=', 'waiting_approve')->limit(1)
            )
            ->columns([
                Tables\Columns\TextColumn::make('user_name')
                    ->formatStateUsing(fn(string $state): string => Str::of($state)->ucwords()),
                Tables\Columns\TextColumn::make('car_name')
                    ->formatStateUsing(fn(string $state): string => Str::of($state)->ucwords()),
                Tables\Columns\TextColumn::make('start_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('duration_day')
                    ->formatStateUsing(fn(string $state): string => $state > 1 ? __("{$state} Days") : __("{$state} Day"))
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->money('IDR', locale: 'id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('driver')
                    ->label('Using the driver')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        '1' => 'success',
                        '0' => 'gray',
                        '' => 'gray',
                    })
                    ->formatStateUsing(function (string $state): string {
                        if ($state) {
                            return 'Yes';
                        } else {
                            return 'No';
                        }
                    })
                    ->sortable(),
            ])
            ->headerActions([
                Action::make('See All')
                    ->button()
                    ->size(ActionSize::Small)
                    ->color('primary')
                    ->action(function ($data,  $record) {
                        redirect()->route('filament.admin.resources.transactions.index');
                    })
            ])
            ->paginated(false);
    }
}
