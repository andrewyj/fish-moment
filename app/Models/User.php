<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
    
    //verify status
    const VERIFY_STATUS_WAITING    = 0;
    const VERIFY_STATUS_PROCESSING = 1;
    const VERIFY_STATUS_PASS       = 2;
    
    protected $casts = [
        'photos' => 'array',
    ];
    
    protected $guarded = ['id'];
    
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->invitation_code) {
                $model->invitation_code = static::findAvailableCode();
                if (!$model->invitation_code) {
                    return false;
                }
            }
        });
    }
    
    public static function genderMaps() {
        return [
            self::GENDER_FEMALE => '女',
            self::GENDER_MALE => '男'
        ];
    }
    
    public static function verifyStatusMaps() {
        return [
            self::VERIFY_STATUS_WAITING    => '待审核',
            self::VERIFY_STATUS_PROCESSING => '审核中',
            self::VERIFY_STATUS_PASS       => '审核通过',
        ];
    }
    
    /**
     * 获取邀请码
     * @return bool|string
     */
    public static function findAvailableCode()
    {
        for ($i = 0; $i < 10; $i++) {
            $invitationCode = hash('sha256', Str::random(60) . uniqid());
            if (!static::query()->where('invitation_code', $invitationCode)->exists()) {
                return $invitationCode;
            }
        }
        Log::warning('find invitation code failed');
        
        return false;
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
