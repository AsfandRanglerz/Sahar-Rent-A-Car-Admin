<?php

namespace App\Models;

use App\Models\UserDocument;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens; 
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens,HasFactory;

    protected $guarded=[];

    public function documents()
{
    return $this->hasOne(UserDocument::class);
}
}
