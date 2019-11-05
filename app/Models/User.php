<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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
    
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->token) {
                $model->token = static::findAvailableToken();
                if (!$model->token) {
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
    
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    
    public function getJWTCustomClaims()
    {
        return [];
    }
    
    /**
     * 获取token
     * @return bool|string
     */
    public static function findAvailableToken()
    {
        for ($i = 0; $i < 10; $i++) {
            $token = hash('sha256', Str::random(60) . uniqid());
            if (!static::query()->where('token', $token)->exists()) {
                return $token;
            }
        }
        Log::warning('find order token failed');
        
        return false;
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
}
