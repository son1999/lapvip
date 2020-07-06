<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    protected $table = 'categories';
    public $timestamps = false;
    protected static $cat = [];
    protected static $splitKey = '_';
    protected static $type = [
        '1' => 'Sản phẩm',
        '2' => 'Tin tức',
        '3' => 'Trả góp',
    ];
    public static $KEY = 'category';


    public function lang(){
        $lang = config('app.locales');
        return isset($lang[$this->lang]) ? $lang[$this->lang] : 'vi';
    }

    public function type(){
        return isset(self::$type[$this->type]) ? self::$type[$this->type] : '1';
    }

    public function products() {
        return $this->hasMany('\App\Models\Product','cat_id','id')->orderByRaw('sort, title');
    }

    public function getImageUrl($size = 'medium'){
        return \ImageURL::getImageUrl($this->image, self::$KEY, $size);
    }

    public function getIcon($size = 'original'){
        return \ImageURL::getImageUrl($this->icon, self::$KEY, $size);
    }
    public function getIconHover($size = 'original'){
        return \ImageURL::getImageUrl($this->icon_hover, self::$KEY, $size);
    }
    public function link(){
        switch($this->type){
            case 1:
                return '';
            case 2:
                return '';
        }
        return '';
        //return route('product.list', ['safe_title' => str_slug($this->name), 'id' => $this->id]);
    }

    public static function getType(){
        return self::$type;
    }

    public static function getCateById($id, $type = 1,$with_count = false) {
        $wery = self::where('id',$id)->where('status','>',0)->where('type', $type);

        if($with_count) {
            $wery->withCount(['products' => function($q) {
                $q->where('status', 2);
            }]);
        }

        return $wery->first();
    }

    public static function getCat($type = 0, $lang = '', $imgSize = ''){
        if(empty($lang)){
            $lang = \Lib::getDefaultLang();
        }
        $key = $type . '-' . $lang;
        if(empty(self::$cat[$key])) {
            $sql = [];
            if ($type >= 0) {
                $sql[] = ['type', '=', $type];
            }
            $sql[] = ['lang', '=', $lang];
            $sql[] = ['status', '>', 0];

            $data = self::where($sql)
                ->orderByRaw('type, pid, sort DESC, title')
                ->get()
                ->keyBy('id');
            $cat = [];
            if ($type < 0) {
                foreach ($data as $k => $v) {
                    if (!isset($cat[$v->type])) {
                        $cat[$v->type] = [
                            'title' => self::$type[$v->type],
                            'type' => $v->type,
                            'cats' => []
                        ];
                    }
                    $cat[$v->type]['cats'][$v->id] = $v;
                }
                foreach ($cat as $k => $v){
                    $cat[$k]['cats'] = self::fetchAll($v['cats'], $imgSize);
                }
            } else {
                $cat = self::fetchAll($data, $imgSize);
            }
            self::$cat[$key] = $cat;
        }
        return self::$cat[$key];
    }

    public static function fetchAll($data, $imgSize = ''){
        $cat = [];
        foreach ($data as $k => $v) {
            if ($v->pid == 0) {
                $cat[self::$splitKey.$v->id] = self::fetchCat($v, $imgSize);
                unset($data[$k]);
            } elseif (isset($cat[self::$splitKey.$v->pid])) {
                $cat[self::$splitKey.$v->pid]['sub'][self::$splitKey.$v->id] = self::fetchCat($v,$imgSize);
                unset($data[$k]);
            }
        }
        foreach ($data as $v) {
            foreach ($cat as $pid => $item){
                foreach ($item['sub'] as $id => $sub){
                    if(self::$splitKey.$v->pid == $id){
                        $cat[$pid]['sub'][$id]['sub'][self::$splitKey.$v->id] = self::fetchCat($v,$imgSize);
                    }
                }
            }
        }
        return $cat;
    }

    public static function fetchCat($cat, $imgSize = ''){
        $out = $cat->toArray();
        $out['image'] = $cat->getImageUrl($imgSize);
        $out['link'] = $cat->link();
        $out['sub'] = [];
        return $out;
    }

    public function filter_cates()
    {
        return $this->belongsToMany('App\Models\FilterCate', 'filter_cate_pivot', 'cate_id', 'filter_cate_id');
    }

    public static function getCate($pid = 0, $id = 0){
        $data = self::where('id', '=', !empty($id) && $id != 0 ? $id : $pid)->where('lang', \Lib::getDefaultLang())->where('status', 1)->first();
        if ($data['pid'] != 0 ){
            $cate_parent = self::where('id', $data['pid'])->where('lang', \Lib::getDefaultLang())->where('status', 1)->select('title')->first();
            $data['cate_parent_title'] = $cate_parent['title'];
        }
        return $data;
    }

    public static function getCateByArrID($arrID = []){
        return self::where('status', '>', 0)->whereIn('id', $arrID)->where('pid', '<>', 0)->select('id', 'title', 'slug', 'pid')->limit(5)->get()->toArray();

    }
    public static function getDataSeo($type = 0, $id = 0){
        if ($type != 0){
            return self::where('type', $type)->where('lang', \Lib::getDefaultLang())->where('status', '>', 0)->first();
        }
        if ($id != 0){
            return self::where('id', $id)->where('lang', \Lib::getDefaultLang())->where('status', '>', 0)->first();
        }
    }



}
