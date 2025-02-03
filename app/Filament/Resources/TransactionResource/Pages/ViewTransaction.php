<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

use Filament\Infolists;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\IconPosition;
use Illuminate\Support\Str;

class ViewTransaction extends ViewRecord
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Data Transactions')
                    ->schema([
                        Fieldset::make('Car')
                            ->schema([
                                ImageEntry::make('car_image_url')
                                    ->label('Car Image')
                                    ->disk('gcs'),
                                Grid::make(2)
                                    ->schema([
                                        Infolists\Components\TextEntry::make('car_name')
                                            ->label('Name')
                                            ->formatStateUsing(fn(string $state): string => Str::of($state)->ucwords()),
                                        Infolists\Components\TextEntry::make('car_brand')
                                            ->label('Brand')
                                            ->formatStateUsing(fn(string $state): string => Str::of($state)->ucwords()),
                                        Infolists\Components\TextEntry::make('car_year')
                                            ->label('Year'),
                                        Infolists\Components\TextEntry::make('car_price_per_day')
                                            ->label('Price per Day')
                                            ->money('IDR', locale: 'id'),
                                        Infolists\Components\TextEntry::make('car_tax')
                                            ->label('Tax')
                                            ->icon('heroicon-m-percent-badge')
                                            ->iconPosition(IconPosition::After),
                                        Infolists\Components\TextEntry::make('car_discount')
                                            ->label('Discount')
                                            ->icon('heroicon-m-percent-badge')
                                            ->iconPosition(IconPosition::After),
                                    ]),

                            ]),
                        Fieldset::make('User')
                            ->schema(
                                [
                                    Grid::make(3)
                                        ->schema([
                                            Infolists\Components\TextEntry::make('user_name')
                                                ->label('Name')
                                                ->formatStateUsing(fn(string $state): string => Str::of($state)->ucwords()),
                                            Infolists\Components\TextEntry::make('user_email')
                                                ->label('Email'),
                                            Infolists\Components\TextEntry::make('user_phone')
                                                ->label('Phone Number')
                                                ->icon('heroicon-m-document-duplicate')
                                                ->iconPosition(IconPosition::After)
                                                ->copyable()
                                                ->copyMessage('Copied!')
                                                ->copyMessageDuration(1500),
                                        ]),
                                ]
                            ),
                        Fieldset::make('Transction')
                            ->schema(
                                [
                                    Grid::make(3)
                                        ->schema([
                                            Infolists\Components\TextEntry::make('no_invoice')
                                                ->label('No Invoice')
                                                ->icon('heroicon-m-document-duplicate')
                                                ->iconPosition(IconPosition::After)
                                                ->copyable()
                                                ->copyMessage('Copied!')
                                                ->copyMessageDuration(1500),
                                            Infolists\Components\TextEntry::make('start_date')
                                                ->label('Start Date')
                                                ->dateTime(timezone: 'Asia/Jakarta'),
                                            Infolists\Components\TextEntry::make('end_date')
                                                ->label('End Date')
                                                ->dateTime(timezone: 'Asia/Jakarta'),
                                            Infolists\Components\TextEntry::make('duration_day')
                                                ->label('Duration')
                                                ->formatStateUsing(fn(string $state): string => $state . " Day"),
                                            Infolists\Components\TextEntry::make('driver')
                                                ->label('Driver')
                                                ->formatStateUsing(function (string $state): string {
                                                    if ($state) {
                                                        return 'Yes';
                                                    } else {
                                                        return 'No';
                                                    }
                                                }),
                                            Infolists\Components\TextEntry::make('method_payment')
                                                ->label('Method payment')
                                                ->formatStateUsing(fn(string $state): string => Str::of($state)->ucwords()),
                                            Infolists\Components\TextEntry::make('status_payment')
                                                ->label('Status Payment')
                                                ->badge()
                                                ->color(fn(string $state): string => match ($state) {
                                                    'waiting_payment' => 'gray',
                                                    'waiting_approve' => 'warning',
                                                    'reject' => 'danger',
                                                    'paid' => 'success',
                                                })
                                                ->formatStateUsing(function (string $state): string {
                                                    if ($state === 'paid') {
                                                        return 'Paid';
                                                    } else if ($state === 'reject') {
                                                        return 'Rejected';
                                                    } else if ($state === 'waiting_payment') {
                                                        return 'Waiting Payment';
                                                    } else {
                                                        return 'Waiting Approve';
                                                    }
                                                }),
                                            Infolists\Components\TextEntry::make('user_name_approved')
                                                ->label('Approved By')
                                                ->formatStateUsing(fn(string $state): string => Str::of($state)->ucwords())
                                                ->visible(fn($record) => $record && $record->user_name_approved != null),
                                            Infolists\Components\TextEntry::make('reason_rejected')
                                                ->label('Rejected Reason')
                                                ->formatStateUsing(fn(string $state): string => Str::of($state)->ucwords())
                                                ->visible(fn($record) => $record && $record->reason_rejected != null),
                                            Infolists\Components\TextEntry::make('unique_code')
                                                ->label('Unique Code'),
                                            Infolists\Components\TextEntry::make('total_price')
                                                ->label('Total Price')
                                                ->formatStateUsing(fn($record) => "Rp. " . number_format($record->unique_code + $record->total_price, 0, ",", ".")),
                                            ImageEntry::make('payment_image')
                                                ->label('Payment Image')
                                                ->disk('gcs')
                                                ->visible(fn($record) => $record && $record->payment_image != null)
                                                ->url(fn($record): string => 'https://storage.googleapis.com/app_travel_bucket/' . $record->payment_image)
                                                ->openUrlInNewTab(),
                                        ]),
                                ]
                            ),

                    ]),
            ]);
    }
}
