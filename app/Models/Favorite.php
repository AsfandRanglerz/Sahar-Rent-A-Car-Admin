<?php

namespace App\Models;

use App\Models\User;
use App\Models\CarDetails;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Favorite extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function car()
    {
        return $this->belongsTo(CarDetails::class, 'car_id', 'car_id');
    }
}
