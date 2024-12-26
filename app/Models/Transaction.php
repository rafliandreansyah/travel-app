<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'user_name',
        'user_phone',
        'user_email',
        'car_id',
        'car_name',
        'car_brand',
        'car_image_url',
        'car_year',
        'car_price_per_day',
        'car_tax',
        'car_discount',
        'user_approved_id',
        'user_name_approved',
        'user_email_approved',
        'status_payment',
        'method_payment',
        'payment_image',
        'reason_rejected'
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

    public function setUserApprovedAttribute($value)
    {
        $this->attributes['user_approved'] = strtolower($value);
    }
}
