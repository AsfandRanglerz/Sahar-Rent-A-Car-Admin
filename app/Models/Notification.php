<?php

namespace App\Models;

use App\Models\User;
use Laravel\Sanctum\HasApiTokens; 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasApiTokens,HasFactory;
    protected $guarded=[];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }
}
