<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supports extends Model
{
    //
    protected $table = 'supports';
    public $timestamps = false;
    const KEY = 'avatar_supports';

    public function getImageUrl($size = 'original'){
        return \ImageURL::getImageUrl($this->avatar_supports, self::KEY, $size);
    }

    public static function getSup($limit){
        return self::where('status', '>', 1)->limit($limit)->get();
    }
}
