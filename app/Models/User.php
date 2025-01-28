<?php

namespace App\Models;

use App\Models\UserDocument;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function documents()
{
    return $this->hasOne(UserDocument::class);
}
}
