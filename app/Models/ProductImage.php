<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $table = 'product_img';
    public $timestamps = false;
    public static $step = 1;

    protected $appends = [
        'img',
        'imglarge'
    ];

    public function product() {
        return $this->hasOne("\App\Models\Product",'id','objet_id')->where('type','product');
    }

    public function getImageUrl($size = 'original'){
        return \ImageURL::getImageUrl($this->image, Product::KEY, $size);
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