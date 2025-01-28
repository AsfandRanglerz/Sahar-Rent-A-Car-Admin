<?php

namespace App\Models;

use App\Models\DriverDocument;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Driver extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function driverdocument(){
        return $this->hasOne(DriverDocument::class);
    }
}
