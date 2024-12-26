<?php

namespace App\Filament\Resources;

use App\Models\Car;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CarResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CarResource\RelationManagers;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Tabs;

class CarResource extends Resource
{
    protected static ?string $model = Car::class;

    protected static ?string $navigationIcon = 'icon-car';

    protected static ?string $navigationGroup = 'Master Cars';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Tabs::make('Input Car')
                    ->tabs([
                        Tabs\Tab::make('Car Data')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('year')
                                            ->required()
                                            ->numeric(),
                                        Forms\Components\TextInput::make('capacity')
                                            ->required()
                                            ->numeric()
                                            ->suffix('Person'),
                                        Forms\Components\TextInput::make('cc')
                                            ->label('CC')
                                            ->required()
                                            ->numeric(),
                                        Forms\Components\Select::make('fuel_type')
                                            ->options([
                                                'solar' => 'Solar',
                                                'bensin' => 'Bensin',
                                                'listrik' => 'Listrik',
                                            ])
                                            ->required(),
                                        Forms\Components\Select::make('transmission')
                                            ->options([
                                                'manual' => 'Manual',
                                                'automatic' => 'Automatic',
                                            ])
                                            ->required(),
                                        Forms\Components\Select::make('brand_id')
                                            ->relationship('brand', 'name')
                                            ->required(),
                                        Forms\Components\TextInput::make('luggage')
                                            ->numeric(),
                                        Fieldset::make('Pricing')
                                            ->schema([
                                                Grid::make(3)
                                                    ->schema([
                                                        Forms\Components\TextInput::make('price_per_day')
                                                            ->required()
                                                            ->prefix('Rp.')
                                                            ->numeric(),
                                                        Forms\Components\TextInput::make('tax')
                                                            ->numeric()
                                                            ->suffixIcon('heroicon-m-percent-badge'),
                                                        Forms\Components\TextInput::make('discount')
                                                            ->numeric()
                                                            ->suffixIcon('heroicon-m-percent-badge'),
                                                    ]),
                                            ]),
                                    ]),
                                Forms\Components\Textarea::make('description')
                                    ->required()
                                    ->columnSpanFull(),
                                Forms\Components\Toggle::make('active')
                                    ->required(),
                            ]),
                        Tabs\Tab::make('Images')
                            ->schema([
                                Forms\Components\FileUpload::make('image_url')
                                    ->label('Car Photo')
                                    ->image()
                                    ->disk('gcs')
                                    ->directory('cars')
                                    ->visibility('public')
                                    ->required()
                                    ->columnSpanFull(),
                                Repeater::make('imageDetails')
                                    ->label('Car Highlights')
                                    ->relationship()
                                    ->schema([
                                        Forms\Components\FileUpload::make('image_url')
                                            ->label('Car Photo')
                                            ->image()
                                            ->imageEditor()
                                            ->disk('gcs')
                                            ->directory('cars')
                                            ->visibility('public'),
                                    ])
                                    ->columnSpanFull(),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_url')
                    ->label('Car Photo')
                    ->disk('gcs'),
                Tables\Columns\TextColumn::make('name')
                    ->formatStateUsing(fn(string $state): string => Str::of($state)->ucwords())
                    ->searchable(),
                Tables\Columns\TextColumn::make('year')
                    ->sortable(),
                Tables\Columns\TextColumn::make('capacity')
                    ->formatStateUsing(fn(string $state): string => $state > 1 ? __("{$state} People") : __("{$state} Person"))
                    ->sortable(),
                Tables\Columns\TextColumn::make('price_per_day')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                Tables\Columns\TextColumn::make('luggage')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('cc')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('tax')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('discount')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('transmission')
                    ->formatStateUsing(fn(string $state): string => Str::of($state)->ucwords())
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('fuel_type')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('brand.name')
                    ->formatStateUsing(fn(string $state): string => Str::of($state)->ucwords())
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('active')
                    ->boolean(),
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListCars::route('/'),
            'create' => Pages\CreateCar::route('/create'),
            'view' => Pages\ViewCar::route('/{record}'),
            'edit' => Pages\EditCar::route('/{record}/edit'),
        ];
    }
}
