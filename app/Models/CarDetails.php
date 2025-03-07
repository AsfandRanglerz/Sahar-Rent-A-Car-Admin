<?php

namespace App\Models;

use App\Models\Booking;
use App\Models\LoyaltyPoints;
use App\Models\RequestBooking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CarDetails extends Model
{
    use HasFactory;
    protected $guarded =[];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'car_id', 'id'); 
    }

    public function requestBookings()
    {
        return $this->hasMany(RequestBooking::class, 'car_id', 'id');
    }

    public function loyaltyPoints()
    {
        return $this->hasOne(LoyaltyPoints::class, 'car_id', 'car_id');
    }
}
