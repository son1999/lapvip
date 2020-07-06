<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    //
    protected $table = 'videos';
    protected $fillable = ['title', 'image_thumbnail', 'view_count', 'published_at', 'channel_id', 'updated'];
    public $timestamps = false;


    public function groups(){
        return $this->hasOne(VideoGroups::class, 'id', 'gr_id');
    }
    public function categories(){
        return $this->hasOne(Category::class, 'id', 'type');
    }
    public static function getVideoHot($cat = 0){
        $data = self::where('status', '>', 1);
        if ($cat != 0){
            $data->where('type', $cat);
        }
        $data->where('is_top', 1);
        $data->limit(1);
        $data->orderBy('id', 'DESC');
        return $data->first();
    }
    public static function getVideoDefaul($cat = 0){
        $data = self::where('status', '>', 1);
        if ($cat != 0){
            $data->where('type', $cat);
        }
        $data->limit(5);
        return $data->orderBy('id', 'DESC')->get();
    }

}
