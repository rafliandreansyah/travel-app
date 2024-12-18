<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'image_url',
        'year',
        'capacity',
        'luggage',
        'cc',
        'price_per_day',
        'tax',
        'discount',
        'description',
        'transmission',
        'fuel_type',
        'active',
        'brand_id',
    ];


    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function imageDetails(): HasMany
    {
        return $this->hasMany(CarsImageDetail::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function carRenteds(): HasMany
    {
        return $this->hasMany(CarRented::class);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }

    public function setTransmissionAttribute($value)
    {
        $this->attributes['transmission'] = strtolower($value);
    }

    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = strtolower($value);
    }

    public function setFullTypeAttribute($value)
    {
        $this->attributes['fuel_type'] = strtolower($value);
    }
}
