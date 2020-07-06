<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagDetail extends Model
{
    //
    protected $table = 'tag_details';
    public $timestamps = false;
    protected $fillable = ['tag_id','type','object_id'];

    public static function getTags($object_id){
        return self::where('object_id', '=', $object_id)->get()->toArray();
    }

    public static function getNews($tag_id){
        if(is_array($tag_id)){
            return self::whereIn('tag_id', $tag_id)->get()->toArray();
        }
        return self::where('tag_id', '=', $tag_id)->get()->toArray();
    }
}
