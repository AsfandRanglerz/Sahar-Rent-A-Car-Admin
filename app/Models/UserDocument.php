<?php

namespace App\Models;

// use App\Models\User;
// use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserDocument extends Model
{
    use HasFactory;
    protected $guarded = [];

//     public function customer()
// {
//     return $this->belongsTo(Customer::class);
// }
}
