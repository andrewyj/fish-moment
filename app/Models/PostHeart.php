<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostHeart extends Model
{
    use SoftDeletes;
    
    protected $guarded = ['id'];
    
    const TYPE_LIKE    = 1;
    const TYPE_DISLIKE = 0;
}
