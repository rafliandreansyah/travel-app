<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rating extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'car_id',
        'user_id',
        'rating',
        'comment',
        'image_url',
    ];


    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function user(): BelongsTo

    {
        return $this->belongsTo(User::class);
    }
}
