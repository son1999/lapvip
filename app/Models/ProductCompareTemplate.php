<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ProductCompareTemplate extends Model
{
    //
    protected $table = 'product_compare_templates';
    public $timestamps = false;

    public static function getAllFroAdminChoose()
    {
        $wery = self::whereRaw('1=1');
        $wery->select('id','title');
        return $wery->get();
    }

    public static function getById($id )
    {
        $wery = self::where('id',$id);
//        $wery->select('id','title');
        return $wery->first();
    }

    public static function returnProperties(Request $request,&$arr_unset)
    {
        $prop_topics = $request->input('com_prop_topics');
        $arr_unset[] = 'com_prop_topics';
        $arr_topics = [];
        if(!empty($prop_topics)) {
            for($i=0;$i<count($prop_topics);$i++) {
                $arr_unset[] = 'com_property_titles_'.$i;
                $arr_unset[] = 'com_property_values_'.$i;
                $prop_titles = $request->input('com_property_titles_'.$i);
                $prop_values = $request->input('com_property_values_'.$i);
                $arr_props = [];
                if(!empty($prop_titles)){
                    for($j = 0;$j<count($prop_titles);$j++) {
                        $arr_props[] = ['title' => $prop_titles[$j],'value' => $prop_values[$j]];
                    }
                }
                $arr_topics[] = ['title' => $prop_topics[$i],'props' => $arr_props];
            }
        }

        return json_encode($arr_topics,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
    }

}
