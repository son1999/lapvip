<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    //
    protected $table = 'page_static';
    public $timestamps = false;
    public static $type = [
        0 => 'Bình thường',
        1 => 'Hiển thị trong menu Mobile',
        2 => 'Hiển thị trong menu Footer',
        100 => 'Hiển thị trong menu Footer + Mobile',
    ];

    public function getLink($admin = 0){
        if($admin){
            return env('APP_URL').'/static/'.$this->link_seo;
        }
        return route('trangtinh', ['link_seo' => str_slug($this->link_seo)]);
    }

    public function lang(){
        $lang = config('app.locales');
        return isset($lang[$this->lang]) ? $lang[$this->lang] : 'vi';
    }
    public function type(){
        return isset(self::$type[$this->type]) ? self::$type[$this->type] : '---';
    }
    public function getAllLink($keyByLink = false, $type = [0]){
        $allPage = Page::whereStatus(2)
            ->whereLang(\Lib::getDefaultLang())
            ->whereIn('type', $type)
            ->select('link_seo','id','title')
            ->orderByRaw('sort desc, id desc')
            ->get();
        $return = [];
        if(!empty($allPage)){
            foreach ($allPage as $p){
                if($keyByLink){
                    $return[$p->link_seo] = $p;
                }else {
                    $return[$p->title] = $p->getLink();
                }
            }
        }
        return $return;
    }
}

