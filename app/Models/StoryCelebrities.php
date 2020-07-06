<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoryCelebrities extends Model
{
    protected $table = 'story_celebrities';
    public $timestamps = false;
    public static $step = 1;

    protected $appends = [
        'img',
        'imglarge'
    ];

    public function getImageUrl($size = 'original')
    {
        return \ImageURL::getImageUrl($this->image, 'story_celebrities', $size);
    }

    public function getImageUrlCele($size = 'original')
    {
        return \ImageURL::getImageUrl($this->img_celebrities, 'original', $size);
    }



    public function getImageProductUrl($size = 'original')
    {
        return \ImageURL::getImageUrl($this->image, 'product_story', $size);
    }


    public static function getSortInsert($lang = 'vi')
    {
        return self::max('sort') + self::$step;
    }

    public function getImgAttribute()
    {
        return $this->getImageUrl();
    }

    public function getImglargeAttribute()
    {
        return $this->getImageUrl('large');
    }
}