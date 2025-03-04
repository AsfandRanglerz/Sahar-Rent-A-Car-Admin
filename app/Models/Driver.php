<?php

namespace App\Models;

use App\Models\DriverDocument;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Driver extends model
{
    use HasApiTokens, HasFactory;
    protected $guarded=[];

    public function driverdocument(){
        return $this->hasOne(DriverDocument::class);
    }
}
