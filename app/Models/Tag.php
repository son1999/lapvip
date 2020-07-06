<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    //
    protected $table = 'tags';
    public $timestamps = false;
    const TYPE = [
        1 => 'news',
        2 => 'food'
    ];

    public function products(){
        return $this->hasMany(Product::class, 'special_box_home', 'id');
    }

    public static function getTags($type = 1,$for_admin = false){
        $wery = self::where('type', '=', $type);
        if($for_admin) {
            $wery->where('is_special_box',0);
        }

        return $wery->get()->toArray();
    }

    public static function getTagsHome(){
        return self::where('type', 2)
            ->where('is_special_box', 1)
            ->where('status', 1)
            ->get()->toArray();
    }

    public static function getNewsTags($id = 0){
        $data = \DB::table('tags')
            ->join('tag_details', 'tags.id', '=', 'tag_details.tag_id')
            ->join('news', 'news.id', '=', 'tag_details.object_id')
            ->select('tags.title', 'tags.id')
            ->where('tags.type', 1);
        if($id > 0){
            $data = $data->where('news.id', '=', $id);
        }
        return $data->get()->toArray();
    }

    public static function getProductTags($id = 0,$for_admin = false){
        $data = \DB::table('tags')
            ->join('tag_details', 'tags.id', '=', 'tag_details.tag_id')
            ->join('products', 'products.id', '=', 'tag_details.object_id')
            ->select('tags.title', 'tags.id')
            ->where('tags.type', 2);
        if($for_admin) {
            $data = $data->where('tags.is_special_box',0);
        }
        if($id > 0){
            $data = $data->where('products.id', '=', $id);
        }
        return $data->get()->toArray();
    }

    public static function getProductSpecialTags(){
        $data = \DB::table('tags')
            ->select('tags.title', 'tags.id', 'tags.is_show_slide_home', 'tags.pid')
            ->where('tags.type', 2)
            ->where('tags.is_special_box',1)
            ->where('tags.status',1);
        return $data->get()->toArray();
    }
    public static function getTagsID(){
        $data = \DB::table('tags')
            ->select('tags.id')
            ->where('tags.type', 2)
            ->where('is_special_box',1)
            ->where('status',1);
        return $data->get()->toArray();
    }

    public static function addTags($tags, $type, $id){
        //lay thong tin tag
        $tags = explode(',', $tags);
        foreach ($tags as $k => $v){
            $tags[$k] = str_slug($v);
        }
        $tags = self::select('id')
            ->where('type', '=', $type)
            ->whereIn('safe_title', $tags)
            ->get()->toArray();
        if(!empty($tags)) {
            $insertData = [];
            foreach ($tags as $item) {
                $insertData[] = [
                    'object_id' => $id,
                    'tag_id' => $item['id'],
                    'type' => $type
                ];
            }
            if (!empty($insertData)) {
                //xoa het tag cu
                TagDetail::where('object_id', $id)
                    ->where('type', $type)
                    ->delete();
                //chen moi
                TagDetail::insert($insertData);

                return true;
            }
        }
        return false;
    }
}
