<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Company;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CompanyResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CompanyResource\RelationManagers;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                Forms\Components\TextInput::make('travel_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('province')
                    ->options([
                        'aceh' => 'Aceh',
                        'sumatera utara' => 'Sumatera Utara',
                        'sumatera selatan' => 'Sumatera Selatan',
                        'sumatera barat' => 'Sumatera Barat',
                        'bengkulu' => 'Bengkulu',
                        'riau' => 'Riau',
                        'kepulauan riau' => 'Kepulauan Riau',
                        'jambi' => 'Jambi',
                        'lampung' => 'Lampung',
                        'bangka belitung' => 'Bangka Belitung',
                        'kalimantan barat' => 'Kalimantan Barat',
                        'kalimantan timur' => 'Kalimantan Timur',
                        'kalimantan selatan' => 'Kalimantan Selatan',
                        'kalimantan tengah' => 'Kalimantan Tengah',
                        'kalimantan utara' => 'Kalimantan Utara',
                        'banten' => 'Banten',
                        'dki jakarta' => 'DKI Jakarta',
                        'jawa barat' => 'Jawa Barat',
                        'jawa tengah' => 'Jawa Tengah',
                        'di yogyakarta' => 'DI Yogyakarta',
                        'jawa timur' => 'Jawa Timur',
                        'bali' => 'Bali',
                        'nusa tenggara timur' => 'Nusa Tenggara Timur',
                        'nusa tenggara barat' => 'Nusa Tenggara Barat',
                        'gorontallo' => 'Gorontalo',
                        'sulawesi barat' => 'Sulawesi Barat',
                        'sulawesi tengah' => 'Sulawesi Tengah',
                        'sulawesi utara' => 'Sulawesi Utara',
                        'sulawesi tenggara' => 'Sulawesi Tenggara',
                        'sulawesi selatan' => 'Sulawesi Selatan',
                        'maluku utara' => 'Maluku Utara',
                        'maluku' => 'Maluku',
                        'papua barat' => 'Papua Barat',
                        'papua' => 'Papua',
                        'papua tengah' => 'Papua Tengah',
                        'papua pegunungan' => 'Papua Pegunungan',
                        'papua selatan' => 'Papua Selatan',
                        'papua barat daya' => 'Papua Barat Daya',
                    ])
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('city')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('postal_code')
                    ->numeric()
                    ->required()
                    ->length(6),
                Forms\Components\TextInput::make('phone_number')
                    ->tel()
                    ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('travel_name')
                    ->formatStateUsing(fn(string $state): string => Str::of($state)->ucwords())
                    ->searchable(),
                Tables\Columns\TextColumn::make('province')
                    ->formatStateUsing(fn(string $state): string => Str::of($state)->ucwords())
                    ->searchable(),
                Tables\Columns\TextColumn::make('city')
                    ->formatStateUsing(fn(string $state): string => Str::of($state)->ucwords())
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->formatStateUsing(fn(string $state): string => Str::of($state)->ucfirst())
                    ->searchable(),
                Tables\Columns\TextColumn::make('postal_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->searchable(),
                Tables\Columns\IconColumn::make('active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(timezone: 'Asia/Jakarta')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(timezone: 'Asia/Jakarta')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }
}
