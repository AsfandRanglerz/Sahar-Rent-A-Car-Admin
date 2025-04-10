<?php

namespace App\Models;

use App\Models\User;
use App\Models\Driver;
use App\Models\Booking;
use App\Models\Dropoff;
use App\Models\CarDetails;
use App\Models\AssignedRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RequestBooking extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function dropdriver()
{
    return $this->belongsTo(Driver::class, 'dropoff_driver_id');
}
    public function booking()
{
    return $this->hasOne(Booking::class, 'car_id', 'car_id');
}

public function car()
{
    return $this->belongsTo(CarDetails::class, 'car_id', 'car_id');
}

public function user()
{
    return $this->belongsTo(User::class);
}

public function dropoff()
{
    return $this->hasOne(Dropoff::class, 'id', 'id');
}

public function assign()
{
    return $this->hasMany(AssignedRequest::class, 'id', 'request_booking_id');
}
}
