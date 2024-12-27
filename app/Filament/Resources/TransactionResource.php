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
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Illuminate\Support\Carbon;
use Filament\Notifications\Collection;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\Action;

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
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->visible(fn(Transaction $record) => $record && $record->status_payment !== 'waiting'),
                // Tables\Actions\EditAction::make(),
                Action::make('reject')
                    ->button()
                    ->size(ActionSize::Small)
                    ->requiresConfirmation()
                    ->color('danger')
                    ->icon('heroicon-m-x-mark')
                    ->action(fn(Transaction $record) => $record)
                    ->visible(fn(Transaction $record) => $record && $record->status_payment === 'waiting'),
                Action::make('approve')
                    ->button()
                    ->size(ActionSize::Small)
                    ->requiresConfirmation()
                    ->color('success')
                    ->icon('heroicon-m-check')
                    ->visible(fn(Transaction $record) => $record && $record->status_payment === 'waiting'),
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
