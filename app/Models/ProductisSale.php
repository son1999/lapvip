<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Symfony\Component\VarDumper\Dumper\DataDumperInterface;

class ProductisSale extends Model
{
    //
    protected $table = 'product_sale';
    public $timestamps = false;

    public $fillable = ['properties'];

    public function product()
    {
        return $this->hasOne('App\Models\Product','id','product_id');
    }
    public static function returnProperties(Request $request,&$arr_unset)
    {
        $prop_topics_sale = $request->input('prop_topics_sale');
        $arr_unset[] = 'prop_topics_sale';
        $arr_topics_sale = [];
        if(!empty($prop_topics_sale)) {
            for($i=0;$i<count($prop_topics_sale);$i++) {
                $arr_unset[] = 'property_sale_titles_'.$i;
                $arr_unset[] = 'property_sale_values_'.$i;
                $prop_titles_sale = $request->input('property_sale_titles_'.$i);
                $prop_values_sale = $request->input('property_sale_values_'.$i);
                $arr_props_sale = [];
                if(!empty($prop_titles)){
                    for($j = 0;$j<count($prop_titles);$j++) {
                        $arr_props_sale[] = ['title' => $prop_titles_sale[$j],'value' => $prop_values_sale[$j]];
                    }
                }
                $arr_topics_sale[] = ['title' => $prop_topics_sale[$i],'props' => $arr_props_sale];
            }
        }

        return json_encode($arr_topics_sale);
    }
}