<?php

namespace App\Models;

use App\Models\Booking;
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
}
