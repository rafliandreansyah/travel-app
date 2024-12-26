<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use App\Models\Car;
use App\Models\CarRented;
use App\Models\Transaction;
use App\Models\User;
use Exception;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;

    protected function beforeCreate()
    {
        $data = $this->data;
        $durationDay = intval($data['duration_day']);

        // End Date
        $startDate = Carbon::parse($data['start_date']);
        $endDate = $startDate->copy()->addDays($durationDay);
        $data['end_date'] = $endDate;

        // Check conflict booking cars
        $carId = $data['car_id'];
        $conflictRentals = CarRented::where('car_id', $carId)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where(function ($subQuery) use ($startDate, $endDate) {
                    $subQuery->where('start_date', '<=', $endDate)
                        ->where('end_date', '>=', $startDate);
                });
            })
            ->orWhere(function ($query) use ($startDate, $endDate) {
                $query->where(function ($subQuery) use ($startDate, $endDate) {
                    $subQuery->where('end_date', '>=', $startDate)
                        ->where('start_date', '<=', $endDate);
                });
            })
            ->exists();
        if ($conflictRentals) {
            Notification::make()
                ->danger()
                ->title('Car not available!')
                ->body('The car is not available on that date. Please choose another date!')
                ->actions([
                    // Action::make('subscribe')
                    //     ->button()
                    //     ->url(route('subscribe'), shouldOpenInNewTab: true),
                ])
                ->send();
            $this->halt();
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $durationDay = intval($data['duration_day']);

        // Invoice number
        $invoiceNumber = generateInvoice($durationDay);
        $data['no_invoice'] = $invoiceNumber;

        // Date
        $startDate = Carbon::parse($data['start_date']);
        $endDate = $startDate->addDays($durationDay);
        $data['start_date'] = $startDate;
        $data['end_date'] = $endDate;

        // User data
        $user = User::where('id', $data['user_id'])->first();
        $data['user_name'] = $user->name;
        $data['user_email'] = $user->email;
        $data['user_phone'] = $user->phone_number;

        // Car data
        $car = Car::where('id', $data['car_id'])->first();
        $data['car_name'] = $car->name;
        $data['car_brand'] = $car->brand->name;
        $data['car_year'] = $car->year;
        $data['car_price_per_day'] = $car->price_per_day;
        $data['car_image_url'] = $car->image_url;
        $carPricePerDay = $car->price_per_day;
        $data['car_price_per_day'] = $carPricePerDay;
        $discount = $car->discount;
        if ($discount) {
            $data['car_discount'] = $discount;
        } else {
            $data['car_discount'] = 0;
        }
        $tax = $car->tax;
        if ($tax) {
            $data['car_tax'] = $tax;
        } else {
            $data['car_tax'] = 0;
        }

        // Calculate total price
        $totalPrice = $carPricePerDay * $durationDay;
        if ($discount) {
            $totalPrice = $totalPrice - ($totalPrice * $discount / 100);
        }
        if ($tax) {
            $totalPrice = $totalPrice + ($totalPrice * $tax / 100);
        }
        $data['total_price'] = $totalPrice;
        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {

        //dd($data);
        $trx = static::getModel()::create($data);
        // dd($data);
        // $trx = Transaction::create($data);
        CarRented::create(
            [
                'car_id' => $data['car_id'],
                'transaction_id' => $trx->id,
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],

            ]
        );
        return $trx;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
