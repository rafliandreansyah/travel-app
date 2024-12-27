<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use Filament\Forms\Components\Actions\Action as ActionsAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Illuminate\Support\Carbon;
use Filament\Notifications\Collection;
use Filament\Notifications\Notification;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\SelectFilter;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'icon-coin';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DateTimePicker::make('start_date')
                    ->required(),
                Forms\Components\TextInput::make('duration_day')
                    ->required()
                    ->numeric()
                    ->suffix('Day'),
                Forms\Components\Select::make('user_id')
                    ->relationship(
                        'user',
                        'name',
                        modifyQueryUsing: fn(Builder $query) => $query->where('active', 1)
                    )
                    ->required(),

                Forms\Components\Select::make('car_id')
                    ->relationship(
                        'car',
                        'name',
                        modifyQueryUsing: fn(Builder $query) => $query->where('active', 1)
                    )
                    ->required(),
                Forms\Components\Select::make('status_payment')
                    ->options(function ($record) {
                        if ($record) {
                            return [
                                'waiting' => 'Waiting',
                                'reject' => 'Rejected',
                                'paid' => 'Paid',
                            ];
                        } else {
                            return [
                                'waiting' => 'Waiting',
                                'paid' => 'Paid',
                            ];
                        }
                    })
                    ->required()
                    ->live(),
                Forms\Components\Select::make('method_payment')
                    ->options([
                        'transfer' => 'Transfer',
                        'cash' => 'Cash',
                        'auto_payment' => 'Auto Payment',
                    ])
                    ->required(),
                Forms\Components\FileUpload::make('payment_image')
                    ->disk('gcs')
                    ->directory('payments')
                    ->visibility('public')
                    ->columnSpanFull()
                    ->visible(fn(Get $get): bool => $get('status_payment') != null && $get('status_payment') !== 'waiting'),
                Toggle::make('driver')
                    ->label('Using the driver')
                    ->onIcon('heroicon-m-check')
                    ->offIcon('heroicon-m-x-mark'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('no_invoice')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->formatStateUsing(fn(string $state): string => Str::of($state)->ucwords()),
                Tables\Columns\TextColumn::make('car.name')
                    ->formatStateUsing(fn(string $state): string => Str::of($state)->ucwords()),
                Tables\Columns\TextColumn::make('start_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('duration_day')
                    ->formatStateUsing(fn(string $state): string => $state > 1 ? __("{$state} Days") : __("{$state} Day"))
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->money('IDR', locale: 'id')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user_name_approved')
                    ->label('Approved Name')
                    ->formatStateUsing(fn(string $state): string => Str::of($state)->ucwords())
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status_payment')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'waiting' => 'gray',
                        'reject' => 'danger',
                        'paid' => 'success',
                    })
                    ->formatStateUsing(function (string $state): string {
                        if ($state === 'paid') {
                            return 'Paid';
                        } else if ($state === 'reject') {
                            return 'Rejected';
                        } else {
                            return 'Waiting Payment';
                        }
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('reason_rejected')
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Columns\ImageColumn::make('payment_image')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('method_payment')
                    ->formatStateUsing(function (string $state): string {
                        if ($state === 'transfer') {
                            return 'Transfer';
                        } else if ($state === 'cash') {
                            return 'Cash';
                        } else {
                            return 'Auto Payment';
                        }
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status_payment')
                    ->options([
                        'waiting' => 'Waiting',
                        'reject' => 'Rejected',
                        'paid' => 'Paid',
                    ]),
                SelectFilter::make('driver')
                    ->label('Using the driver')
                    ->options([
                        '1' => 'Yes',
                        '0' => 'No',
                    ])
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->visible(fn(Transaction $record) => $record && $record->status_payment !== 'waiting'),
                Action::make('reject')
                    ->button()
                    ->size(ActionSize::Small)
                    ->requiresConfirmation()
                    ->color('danger')
                    ->icon('heroicon-m-x-mark')
                    ->form([
                        Textarea::make('reason_rejected')
                            ->label('Reason Reject')
                            ->required()
                    ])
                    ->action(function (array $data, Transaction $record) {
                        $record->carRented->delete();
                        $user = auth()->user();
                        $record->update([
                            'status_payment' => 'reject',
                            'user_approved_id' => $user->id,
                            'user_name_approved' => $user->name,
                            'user_email_approved' => $user->email,
                            'reason_rejected' => $data['reason_rejected'],
                        ]);
                        Notification::make()
                            ->title('Transaction rejected')
                            ->success()
                            ->send();
                    })
                    ->visible(fn(Transaction $record) => $record && $record->status_payment === 'waiting'),
                Action::make('approve')
                    ->button()
                    ->size(ActionSize::Small)
                    ->requiresConfirmation()
                    ->color('success')
                    ->icon('heroicon-m-check')
                    ->visible(fn(Transaction $record) => $record && $record->status_payment === 'waiting')
                    ->action(function (Transaction $record) {
                        $user = auth()->user();
                        $record->update([
                            'status_payment' => 'paid',
                            'user_approved_id' => $user->id,
                            'user_name_approved' => $user->name,
                            'user_email_approved' => $user->email,
                        ]);
                        Notification::make()
                            ->title('Transaction approved')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'view' => Pages\ViewTransaction::route('/{record}'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
