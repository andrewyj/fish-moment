<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFollower extends Model
{
    public $timestamps = false;
    
    protected $guarded = ['id'];
}
