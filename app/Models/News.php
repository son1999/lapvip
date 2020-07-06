<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tag;
use App\Models\TagDetail;

class News extends Model
{
    //
    protected $table = 'news';
    public $timestamps = false;

    public function category(){
        return $this->hasOne('App\Models\Category', 'id', 'cat_id');
    }

    public function getImageUrl($size = 'original'){
        return \ImageURL::getImageUrl($this->image, 'news', $size);
    }
    public function getImageSeoUrl($size = 'original'){
        return \ImageURL::getImageUrl($this->image_seo, 'news', $size);
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
            ['show_home', '>', 0]
        ];
        $data = self::with(['cates' => function($q){
            $q->select('id', 'title');
        }] )->select('id', 'title', 'title_seo', 'image', 'body', 'cat_id')
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
    public function cates(){
        return $this->hasOne(Category::class, 'id', 'cat_id');
    }

    public static function getWatchalot($cate_id, $lang, $limit){
        $data = \DB::table('news')
            ->leftJoin('categories', 'categories.id', '=', 'news.cat_id')
            ->where('categories.id', $cate_id)
            ->where('news.status', '>', 1)
            ->where('news.lang', $lang)
            ->where('news.cat_id', $cate_id)
            ->whereNull('news.links_video')
            ->where('news.watch_a_lot', '>', 0)
            ->select('news.title', 'news.image', 'categories.title as title_cate')
            ->orderBy('news.watch_a_lot', 'DESC')
            ->limit($limit)
            ->get();
        return $data;
    }

    public static function getVideoDefaul($cond, $cat = 0){
        $wery = self::where($cond);
        if($cat != 0 ){
            $wery->where('cat_id', '=', $cat);
        }
        $wery->whereNotNull('links_video');
        $wery->select('id', 'title', 'links_video', 'hot_new');
        $wery->orderBy('id', 'DESC');

        $data = [];
        if (!empty($wery->get())){
            foreach ($wery->get()->toArray() as &$item){
                if (!empty($item['links_video'])){
                    $item['video_id'] = \Lib::youtube_id($item['links_video']);
                    $item['video_info'] = \Lib::youtube_data_custome($item['video_id']);
                }
                $data[] = $item;
            }
        }
//        dd($data);
        return $data;
    }
    public static function getNewTop($cond, $cat = 0){
        $wery = self::where($cond);
        if ($cat != 0){
            $wery->where('cat_id', '=', $cat);
        }
        $wery->whereNull('links_video');
        $wery->where(function ($q){
            $q->where('list_hot', 1)->orWhere('hot_new', 1);
        });
        $wery->select('title', 'alias', 'image', 'hot_new', 'list_hot','created');
        $wery->orderBy('id', 'DESC');
        $wery->limit(6);

        return $wery->get();

    }
}

