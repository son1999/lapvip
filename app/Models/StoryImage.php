<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoryImage extends Model
{
    protected $table = 'story_image';
    public $timestamps = false;
    public static $step = 1;

    protected $appends = [
        'img',
        'imglarge'
    ];
    public function getImageUrl($size = 'original')
    {
        return \ImageURL::getImageUrl($this->image, 'story_image', $size);
    }

    public function storyProduct() {
        return $this->hasOne(Room::class,'id','objet_id')->where('type','product_story');
    }

    public function getImageProductUrl($size = 'original'){
        return \ImageURL::getImageUrl($this->image, 'product_story', $size);
    }


    public static function getSortInsert($lang = 'vi'){
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