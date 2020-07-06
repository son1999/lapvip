<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeatureSmall extends Model
{
    //
    protected $table = 'features_small';
    public $timestamps = false;

    const OPTIONS = [
        '1' => 'Banner Small Left ',

        '2' => 'Banner Small Right'
    ];

    public function getImageUrl($size = 'original'){
        return \ImageURL::getImageUrl($this->image, 'features_small', $size);
    }

    public function lang(){
        $lang = config('app.locales');
        return isset($lang[$this->lang]) ? $lang[$this->lang] : 'vi';
    }

    public static function getBanner($position = '1', $lang = '', $limit = 0){
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

    public static function getBannerByLang($lang = 'vi'){
        return self::where([
            ['lang', '=', $lang],
            ['status', '>', 0],
        ])->get();
    }

    public function positions(){
        $all = ( $this->positions);
        if(!empty($all)){
            $tmp = [];
                if(isset(self::OPTIONS[$all])){
                    $tmp[$all] = self::OPTIONS[$all];
                }
            return $tmp;
        }
        return false;
    }
}
