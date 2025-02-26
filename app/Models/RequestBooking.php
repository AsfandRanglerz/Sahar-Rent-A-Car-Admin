<?php

namespace App\Models;

use App\Models\Driver;
use App\Models\Booking;
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

    public function booking()
{
    return $this->hasOne(Booking::class, 'car_id', 'car_id');
}

}
