<?php

namespace App\Models;

use App\Models\DriverDocument;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class driversregister extends Model
{
    use HasApiTokens;
    protected $guarded = [];

    public function document()
    {
        return $this->hasOne(DriverDocument::class, 'driver_id', 'id');
    }
}
