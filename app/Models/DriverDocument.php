<?php

namespace App\Models;

// use App\Models\Driver;
use App\Models\driversregister;
use App\Models\LicenseApproval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DriverDocument extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function driversregister(){
        return $this->belongsTo(driversregister::class);
    }

    public function licenseApproval()
{
    return $this->hasOne(LicenseApproval::class, 'driver_id','driver_id');
}

}
