<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocialPool extends Model
{
    use SoftDeletes;
    
    protected $guarded = ['id'];
    
    public function users() {
        return $this->belongsToMany(User::class, UserSocialPool::class);
    }
    
    public function school() {
        return $this->belongsTo(School::class);
    }
}
