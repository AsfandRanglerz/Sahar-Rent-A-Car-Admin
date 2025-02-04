<?php

namespace App\Models;

// use App\Models\Driver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DriverDocument extends Model
{
    use HasFactory;
    protected $guarded = [];

    // public function driver(){
    //     return $this->belongsTo(Driver::class);
    // }
}
