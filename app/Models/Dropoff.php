<?php

namespace App\Models;

use App\Models\Driver;
use App\Models\Booking;
use App\Models\RequestBooking;
use App\Models\AssignedRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dropoff extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function requestBooking()
{
    return $this->belongsTo(RequestBooking::class, 'id', 'id');
}
public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function booking()
{
    return $this->hasOne(Booking::class, 'car_id', 'car_id');
}

public function assign()
{
    return $this->hasMany(AssignedRequest::class, 'request_booking_id', 'id');
}
}