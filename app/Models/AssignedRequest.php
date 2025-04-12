<?php

namespace App\Models;

use App\Models\Driver;
use App\Models\RequestBooking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssignedRequest extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function assignedbooking()
    {
        return $this->belongsTo(RequestBooking::class, 'request_booking_id', 'id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id', 'id');
    }

    public function dropdriver()
    {
        return $this->belongsTo(Driver::class, 'dropoff_driver_id');
    }

    public function booking()
    {
        return $this->hasOne(RequestBooking::class, 'car_id', 'id');
    }
}
