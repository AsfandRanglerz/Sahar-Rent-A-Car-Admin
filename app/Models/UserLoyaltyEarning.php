<?php

namespace App\Models;

use App\Models\User;
use App\Models\LoyaltyPoints;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserLoyaltyEarning extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function loyaltyPoints()
    {
        return $this->belongsTo(LoyaltyPoints::class);
    }
}
