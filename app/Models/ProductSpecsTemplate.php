<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ProductSpecsTemplate extends Model
{
    //
    protected $table = 'product_specs_templates';
    public $timestamps = false;

    public static function getAllFroAdminChoose()
    {
        $wery = self::whereRaw('1=1');
        $wery->where('status', '>', 0);
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
        $prop_topics = $request->input('prop_topics');
        $arr_unset[] = 'prop_topics';
        $arr_topics = [];
        if(!empty($prop_topics)) {
            for($i=0;$i<count($prop_topics);$i++) {
                $arr_unset[] = 'property_titles_'.$i;
                $arr_unset[] = 'property_values_'.$i;
                $prop_titles = $request->input('property_titles_'.$i);
                $prop_values = $request->input('property_values_'.$i);
                $arr_props = [];
                if(!empty($prop_titles)){
                    for($j = 0;$j<count($prop_titles);$j++) {
                        $arr_props[] = ['title' => $prop_titles[$j],'value' => str_replace('"','\'',$prop_values[$j])];
                    }
                }
                $arr_topics[] = ['title' => $prop_topics[$i],'props' => $arr_props];
            }
        }

        return json_encode($arr_topics,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
    }
//    public static function returnPromoteProperties(Request $request,&$arr_unset)
//    {
//        $prop_topics_sale = $request->input('prop_topics_sale');
//        $arr_unset[] = 'prop_topics_sale';
//        $arr_topics_sale = [];
//        if(!empty($prop_topics_sale)) {
//            for($i=0;$i<count($prop_topics_sale);$i++) {
//                $arr_unset[] = 'property_sale_titles_'.$i;
//                $arr_unset[] = 'property_sale_values_'.$i;
//                $prop_titles_sale = $request->input('property_sale_titles_'.$i);
//                $prop_values_sale = $request->input('property_sale_values_'.$i);
//                $arr_props_sale = [];
//                if(!empty($prop_titles_sale)){
//                    for($j = 0;$j<count($prop_titles_sale);$j++) {
//                        $arr_props_sale[] = ['title' => $prop_titles_sale[$j],'value' => $prop_values_sale[$j]];
//                    }
//                }
//                $arr_topics_sale[] = ['title' => $prop_topics_sale[$i],'props' => $arr_props_sale];
//            }
//        }
//        return json_encode($arr_topics_sale,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
//    }
}
