<?php

namespace App\Models;

// use App\Models\UserDocument;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    use HasFactory,HasApiTokens;

    protected $guarded = [];

    // public function userDocument()
    // {
    //     return $this->hasOne(UserDocument::class);
    // }
}
