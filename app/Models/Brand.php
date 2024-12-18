<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'description'
    ];

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }

    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = strtolower($value);
    }
}
