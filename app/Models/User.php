<?php

namespace App\Models;

use App\Models\UserDocument;
use App\Models\UserLoyaltyEarning;
use Laravel\Sanctum\HasApiTokens; 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens,HasFactory;

    protected $guarded=[];

    public function documents()
{
    return $this->hasOne(UserDocument::class);
}
public function loyaltyEarnings()
{
    return $this->hasOne(UserLoyaltyEarning::class, 'user_id');
}
}
