<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'no_invoice',
        'start_date',
        'end_date',
        'duration_day',
        'total_price',
        'user_id',
        'car_id',
        'user_approved',
        'status',
        'payment',
        'method',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function carRented(): HasOne
    {
        return $this->hasOne(Car::class);
    }
}
