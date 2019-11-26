<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    
    //disable
    const DISABLED_NO  = 0;
    const DISABLED_YES = 1;
    
    //link_type
    const LINK_TYPE_INNER   = 0;
    const LINK_TYPE_OUTSIDE = 1;
    
    //code
    const CODE_ACTIVITY = 'activity';
    
    public function scopeAvailable($query)
    {
        return $query->where('disabled', self::DISABLED_NO);
    }
    
    public static function codeMaps() {
        return [
            self::CODE_ACTIVITY => '活动',
        ];
    }
    
    public static function linkTypeMaps() {
        return [
            self::LINK_TYPE_INNER   => '内链接',
            self::LINK_TYPE_OUTSIDE => '外链接',
        ];
    }
}
