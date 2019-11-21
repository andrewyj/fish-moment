<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tests\Models\Tag;

class Post extends Model
{
    use SoftDeletes;
    
    //资源类型
    const RESOURCE_TYPE_IMAGE = 0;
    const RESOURCE_TYPE_VIDEO = 1;
    
    //审核状态
    const VERIFY_STATUS_WAITING    = 0;
    const VERIFY_STATUS_PROCESSING = 1;
    const VERIFY_STATUS_PASS       = 2;
    
    protected $guarded = ['id'];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'resource_urls' => 'array',
    ];
    
    public static function resourceTypeMaps() {
        return [
            self::RESOURCE_TYPE_IMAGE => '图片',
            self::RESOURCE_TYPE_VIDEO => '视频',
        ];
    }
    
    public static function verifyStatusMaps() {
        return [
            self::VERIFY_STATUS_WAITING    => '待审核',
            self::VERIFY_STATUS_PROCESSING => '审核中',
            self::VERIFY_STATUS_PASS       => '审核通过',
        ];
    }
    
    public function likes() {
        return $this->belongsToMany(User::class, PostHeart::class)->wherePivot('type', PostHeart::TYPE_LIKE);
    }
    
    
    public function dislikes() {
        return $this->belongsToMany(User::class, PostHeart::class)->wherePivot('type', PostHeart::TYPE_DISLIKE);
    }
    
    public function tags() {
        return $this->belongsToMany(Tag::class, TagPost::class);
    }
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function school() {
        return $this->belongsTo(School::class);
    }
}
