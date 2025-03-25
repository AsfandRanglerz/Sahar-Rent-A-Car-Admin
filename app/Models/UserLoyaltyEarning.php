<?php

namespace App\Models;

use App\Models\User;
use App\Models\Booking;
use App\Models\CarDetails;
use App\Models\LoyaltyPoints;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserLoyaltyEarning extends Authenticatable
{
    use HasApiTokens,HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function loyaltyPoints()
    {
        return $this->belongsTo(LoyaltyPoints::class);
    }

    public function booking()
{
    return $this->belongsTo(Booking::class, 'booking_id', 'id');
}
public function car()
{
    return $this->belongsTo(CarDetails::class, 'car_id', 'id');
}
}
