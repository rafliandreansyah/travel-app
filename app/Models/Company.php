<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'travel_name',
        'province',
        'city',
        'address',
        'postal_code',
        'phone_number',
        'active',
    ];

    public function setTravelNameAttribute($value)
    {
        $this->attributes['travel_name'] = strtolower($value);
    }

    public function setProvinceAttribute($value)
    {
        $this->attributes['province'] = strtolower($value);
    }

    public function setCityAttribute($value)
    {
        $this->attributes['city'] = strtolower($value);
    }

    public function setAddressAttribute($value)
    {
        $this->attributes['address'] = strtolower($value);
    }
}
