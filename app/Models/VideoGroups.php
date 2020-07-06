<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Input;

class VideoGroups extends Model
{
    //
    protected $table = 'videos_groups';
    public $timestamps = false;


    public static function getGroups(){
        return self::where('status', '>', 1)->get();
    }
    public function videos(){
        return $this->hasMany(Video::class, 'gr_id', 'id');
    }

    public static function getGroupsVideo($limit = 0){
        $wery = \DB::table('videos_groups')
            ->leftJoin('videos', 'videos.gr_id', '=', 'videos_groups.id')
            ->select('videos_groups.id as video_groups_id', 'videos.*')
            ->where('videos.status', '>', 1)
            ->where('videos_groups.status', '>', 1 )
            ->where('videos.is_top', 0)
            ->get();
//        if(count($wery) > 0){
//            foreach ($wery as $q => $i) {
//                $def[$i->video_groups_id][] = $i;
//            }
//            if ($limit > 0) {
//                foreach ($def as $q => $i) {
//                    $def_2[$q] = array_slice($i, 0, $limit);
//                }
//                return $def_2;
//            }
//            return $def;
//        }
        return $wery;
    }

    public static function getVideoByGruop($cat = 0){
        $wery = self::with(['videos' => function($q) use($cat){
            if ($cat != 0){
                $q->where('type', $cat);
            }
            $q->where('videos.status', '>', 1);
            $q->where('videos.is_top', 0);
            $q->limit(5);
        }])->where('videos_groups.status', '>', 1)
            ->select('videos_groups.title as title_groups', 'videos_groups.description', 'videos_groups.id');
        return $wery->paginate(5)->appends(Input::except('page'));




    }

}
