<?php

namespace App\Models;

use App\Models\DriverDocument;
use App\Models\LicenseApproval;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Driver extends Authenticatable
{
    use HasApiTokens, HasFactory;
    protected $guarded=[];

    public function driverdocument(){
        return $this->hasOne(DriverDocument::class);
    }

    public function license()
    {
        return $this->hasOne(LicenseApproval::class, 'driver_id');
    }
}
