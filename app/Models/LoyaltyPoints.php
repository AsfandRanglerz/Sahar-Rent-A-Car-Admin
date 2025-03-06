<?php

namespace App\Models;

use App\Models\Booking;
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

}
