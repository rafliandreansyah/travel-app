<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use App\Models\Car;
use App\Models\Transaction;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $durationDay = intval($data['duration_day']);

        // Invoice number
        $invoiceNumber = generateInvoice($durationDay);
        $data['no_invoice'] = $invoiceNumber;

        // End Date
        $startDate = Carbon::parse($data['start_date']);
        $endDate = $startDate->addDays($durationDay);
        $data['end_date'] = $endDate;

        // Total Price
        $car = Car::where('id', $data['car_id'])->first();
        $totalPrice = $car->price_per_day * $durationDay;
        $discount = $car->discount;
        if ($discount) {
            $totalPrice = $totalPrice - ($totalPrice * $discount / 100);
        }
        $tax = $car->tax;
        if ($tax) {
            $totalPrice = $totalPrice + ($totalPrice * $tax / 100);
        }
        $data['total_price'] = $totalPrice;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
