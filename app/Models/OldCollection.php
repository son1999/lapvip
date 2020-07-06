<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tag;
use App\Models\TagDetail;

class OldCollection extends Model
{
    //
    protected $table = 'old_collection';
    public $timestamps = false;

    public function category(){
        return $this->hasOne('App\Models\Category', 'id', 'cat_id');
    }

    public function getImageUrl($size = 'original'){
        return \ImageURL::getImageUrl($this->image, 'news', $size);
    }

    public function getBannerUrl($size = 'original'){
        return \ImageURL::getImageUrl($this->banner, 'news', $size);
    }

    public function getLink(){
        //return self::getLinkDetail($this->title_seo, $this->id, $this->type);
        return !empty($this->link) ? $this->link : '#';
    }

    public static function getLinkDetail($title_seo = '', $id = 0, $type = 'news'){
        return route($type.'.detail', ['safe_title' => str_slug($title_seo), 'id' => $id]);
    }

    public function lang(){
        $lang = config('app.locales');
        return isset($lang[$this->lang]) ? $lang[$this->lang] : 'vi';
    }

    public static function getListNew($lang = 'vi', $limit = 7, $except = '', $type = 'all'){
        $cond = [
            ['status', '=', 2],
            ['published', '>', 0],
            ['lang', '=', $lang],
        ];
        // if($type != 'all'){
        //     $cond[] = ['type', '=', $type];
        // }
        $data = self::select('id', 'title', 'title_seo', 'image', 'sort_body')
            ->where($cond);
        if(!empty($except)){
            if(!is_array($except)){
                $except = [$except];
            }
            $data = $data->whereNotIn('id', $except);
        }
        return $data->orderBy('published', 'desc')
            ->limit($limit)
            ->get();
    }

    public static function getListNewHome($lang = 'vi', $limit = 7, $except = '', $type = 'all'){
        $cond = [
            ['status', '=', 2],
            ['published', '>', 0],
            ['lang', '=', $lang],
        ];
        $data = self::select('id', 'title', 'title_seo', 'image', 'body')
            ->where($cond)->whereNull('links_video');
        if(!empty($except)){
            if(!is_array($except)){
                $except = [$except];
            }
            $data = $data->whereNotIn('id', $except);
        }
        return $data->orderBy('published', 'desc')
            ->limit($limit)
            ->get();
    }

    public static function getRelated($lang = 'vi', $limit = 3, $id, $type = 'news'){
        //lay toan bo danh sach tag cua tin
        $tags = TagDetail::getTags($id);

        $ids = [];
        foreach($tags as $item){
            $ids[] = $item['tag_id'];
        }

        //lay toan bo tin cung tag
        $news = TagDetail::getNews($ids);
        $ids = [];
        foreach($news as $item){
            if($item['id'] != $id && !in_array($item['id'], $ids)) {
                $ids[] = $item['object_id'];
            }
        }
        return self::select('id', 'title', 'title_seo', 'image', 'published')
            ->where([
                ['status', '=', 2],
                ['published', '>', 0],
                ['lang', '=', $lang],
            ])
            ->whereIn('id', $ids)
            ->limit($limit)
            ->get();
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function user() {
        return $this->hasOne(User::class, 'id', 'uid');
    }
    public function cates()
    {
        return $this->belongsToMany('App\Models\Category', 'old_collection_cate_pivot', 'old_collection_cate_id', 'cate_id');
    }

    public static function getProductPageOldCollection($id, $limit = 0) {
            $wery = \DB::table('old_collection')
            ->leftJoin('old_collection_cate_pivot', 'old_collection_cate_pivot.old_collection_cate_id', '=', 'old_collection.id')
            ->leftJoin('categories', 'categories.id', '=', 'old_collection_cate_pivot.cate_id')
            ->leftJoin('products', 'products.cat_id', '=', 'categories.id')
            ->select('categories.title as cat_title', 'categories.id as cat_id', 'products.*')
            ->where('products.status', '>', 1)->where('old_collection.id', $id)->get();



//        foreach ($wery as $q => $i) {
//            $def[$i->cat_title][] = $i;
//        }
//        if ($limit > 0) {
//            foreach ($def as $q => $i) {
//                $def_2[$q] = array_slice($i, 0, $limit);
//            }
//            return $def_2;
//        }
//        return $def;
    }

}

