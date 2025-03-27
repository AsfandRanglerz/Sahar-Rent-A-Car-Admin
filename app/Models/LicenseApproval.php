<?php

namespace App\Models;

use App\Models\Driver;
use App\Models\driversregister;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LicenseApproval extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function driver()
    {
        return $this->belongsTo(Driver::class,'driver_id');
    }

    public function driverDocument()
{
    return $this->belongsTo(DriverDocument::class, 'driver_id');
}

}
