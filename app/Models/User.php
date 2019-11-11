<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;

class User  extends Authenticatable implements JWTSubject
{
    use SoftDeletes;
    
    //gender
    const GENDER_MALE   = 1;
    const GENDER_FEMALE = 2;
    
    //disable
    const DISABLED_NO  = 0;
    const DISABLED_YES = 1;
    
    //auth type
    const AUTH_TYPE_WECHAT = 'weixin';
    
    protected $casts = [
        'photos' => 'array',
    ];
    
    protected $guarded = ['deleted_at'];
    
    public static function genderMaps() {
        return [
            self::GENDER_FEMALE => '女',
            self::GENDER_MALE => '男'
        ];
    }
    
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    
    public function getJWTCustomClaims()
    {
        return [];
    }
    
    /**
     * 未被禁用用户
     * @param $query
     * @return mixed
     */
    public function scopeAvailable($query)
    {
        return $query->where('disabled', self::DISABLED_NO);
    }
    
    public function school() {
        return $this->belongsTo(School::class);
    }
    
    public function posts() {
        return $this->hasMany(Post::class);
    }
    
    public function socialPools() {
        return $this->belongsToMany(SocialPool::class, UserSocialPool::class);
    }
    
    public function followers() {
        return $this->belongsToMany(User::class, UserFollower::class, 'user_id', 'follower_id');
    }
    
    public function followings() {
        return $this->belongsToMany(User::class, UserFollower::class, 'follower_id', 'user_id');
    }
}
