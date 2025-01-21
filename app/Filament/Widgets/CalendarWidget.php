<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\TransactionResource;
use App\Models\Transaction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;
use Saade\FilamentFullCalendar\Data\EventData;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{

    protected static ?string $heading = 'Car Rental Calendar';
    protected static ?int $sort = 10;

    /**
     * FullCalendar will call this function whenever it needs new event data.
     * This is triggered when the user clicks prev/next or switches views on the calendar.
     */
    public function fetchEvents(array $fetchInfo): array
    {
        // You can use $fetchInfo to filter events by date.
        // This method should return an array of event-like objects. See: https://github.com/saade/filament-fullcalendar/blob/3.x/#returning-events
        // You can also return an array of EventData objects. See: https://github.com/saade/filament-fullcalendar/blob/3.x/#the-eventdata-class
        return Transaction::query()
            ->where('status_payment', '=', 'paid')
            ->where('start_date', '>=', $fetchInfo['start'])
            ->where('end_date', '<=', $fetchInfo['end'])
            ->get()
            ->map(
                fn(Transaction $transaction) => EventData::make()
                    ->id($transaction->id)
                    ->title($transaction->user_name . ' - ' . $transaction->car_name)
                    ->start($transaction->start_date)
                    ->end($transaction->end_date)
                    ->url(
                        url: TransactionResource::getUrl(name: 'view', parameters: ['record' => $transaction]),
                        shouldOpenUrlInNewTab: true
                    )
            )
            ->toArray();
    }
}
