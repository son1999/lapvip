<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    //
    protected $table = 'gallery';
    public $timestamps = false;
    public static $defCatID = 1;
    public static $step = 50;

    public function getImageUrl($size = 'original'){
        return \ImageURL::getImageUrl($this->image, 'gallery', $size);
    }

    public static function getSortInsert($lang = 'vi'){
        return self::where('lang', $lang)->max('sort') + self::$step;
    }

    public static function getImageGallery($cat_id = 1, $json = false){
        $images = Gallery::where('cat_id', $cat_id)->orderByRaw('lang, sort DESC')->get();
        $data = [];
        foreach($images as $image){
            $tmp = $image->toArray();
            $tmp['img'] = $image->image;
            $tmp['image_sm'] = $image->getImageUrl('small');
            $tmp['image'] = $image->getImageUrl('large');
            $tmp['image_org'] = $image->getImageUrl();
            array_push($data, $tmp);
        }
        return $json ? json_encode($data) : $data;
    }
}