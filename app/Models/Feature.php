<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    //
    protected $table = 'features';
    public $timestamps = false;

    const OPTIONS = [
        'big_home' => 'Banner to website - 730 x 315 px',
        'top_right_home' => 'Banner nhỏ góc phải bên trên trang Home - 350 x 90 px',
        'bottom_center_of_big' => 'Banner dài giữa, bên dưới banner to trang chủ - 1110 x 100 px',
        'detail' => 'Banner bên dưới chi tiết sản phẩm - 320 x 120 px',
        'menu' => 'Banner Menu 2 - 207 x 209 px',
        'cart' => 'Banner giỏ hàng',
        'installment' => 'Banner trả góp',
        'compare' => 'Banner so sánh',
        
//        'fae_home' => 'Trang chủ FAE - 600x398 px'
    ];

    const SIZE = [
        'big_home' => ['width' => 730, 'height' => 315],
        'top_right_home' => ['width' => 350, 'height' => 90],
        'bottom_center_of_big' => ['width' => 1110, 'height' => 100],
        'fae_home' => ['width' => 600, 'height' => 398],
        'detail' => ['width' => 320, 'height' => 120],
        'menu' => ['width' =>207, 'height' => 209],
    ];

    public static function getSize($type = 'big_home'){
        if(isset(self::SIZE[$type])){
            return (object)self::SIZE[$type];
        }
        return false;
    }

    public function getImageUrl($size = 'original'){
        return \ImageURL::getImageUrl($this->image, 'feature', $size);
    }

    public function lang(){
        $lang = config('app.locales');
        return isset($lang[$this->lang]) ? $lang[$this->lang] : 'vi';
    }

    public static function getSlides($position = 'fae_home', $lang = '', $limit = 0){
        if(empty($lang)){
            $lang = \Lib::getDefaultLang();
        }
        $data = self::where('lang', $lang)
            ->where('status', '>', 0)
            ->where('positions', 'LIKE', '%'.$position.'%');
        if($limit > 0){
            $data = $data->limit($limit);
        }
        return $data->get();
    }

    public static function getSlideByLangByOption($lang = 'vi'){
        return self::where([
            ['lang', '=', $lang],
            ['status', '>', 0],
        ])->get();
    }
    public static function getSlideByPositions($lang = '', $position = ''){
        $wery = self::where('status','>',0);
        $wery->where('lang', $lang);
        $wery->whereRaw("CONCAT(',',positions,',') like '%,".$position.",%'");
        return $wery;
    }

    public function positions(){
        $all = explode(',', $this->positions);
        if(!empty($all)){
            $tmp = [];
            foreach ($all as $a){
                if(isset(self::OPTIONS[$a])){
                    $tmp[$a] = self::OPTIONS[$a];
                }
            }
            return $tmp;
        }
        return false;
    }
}
