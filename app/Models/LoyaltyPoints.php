<?php

namespace App\Models;

use App\Models\Booking;
use App\Models\CarDetails;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class LoyaltyPoints extends Authenticatable
{
    use HasApiTokens,HasFactory;
    protected $guarded = [];

    public function booking()
{
    return $this->belongsTo(Booking::class, 'booking_id', 'id');
}

public function car()
{
    return $this->belongsTo(CarDetails::class, 'car_id', 'id');
}
}
