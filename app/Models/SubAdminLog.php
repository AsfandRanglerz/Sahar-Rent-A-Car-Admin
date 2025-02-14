<?php

namespace App\Models;

use App\Models\Subadmin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubAdminLog extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function subadmin()
{
    return $this->belongsTo(Subadmin::class, 'subadmin_id');
}
}
