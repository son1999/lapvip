<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\DocBlock\Description;

class FilterCate extends Model
{
    //
    protected $table = 'filter_cates';
    public $timestamps = false;
    const TYPE = [
        'product' => 'product'
    ];

    public function filters() {
        return $this->hasMany('App\Models\Filter','filter_cate_id','id');
    }


    public static function getFilterCates($for_apply_price = false,$prd_cate_id = 0,$type = 'product'){
        $wery = self::with(['filters' => function($q) {
            $q->where('status','>',-1);
        }]);
        $wery->where('status','>',-1);
        if($for_apply_price) {
            $wery->where('status',1);
        }else {
            $wery->where('status',2);
        }

        if($prd_cate_id > 0) {
            $wery->whereHas('cates', function($q) use($prd_cate_id) {
                $q->where('cate_id',$prd_cate_id);
            });
//            $wery->whereRaw("CONCAT('-',category_id,'-') like CONCAT('%-',".(int)$prd_cate_id.",'-%')");
        }

        $wery->where('type', '=', $type);
//        dd($wery->get());
        return $wery->get();
    }
    public static function getFilterCatess($for_apply_price = false,$prd_cate_id = 0,$type = 'product'){
        $wery = self::where('status','>',-1);
        if($for_apply_price) {
            $wery->where('status',1);
        }else {
            $wery->where('status',2);
        }

        if($prd_cate_id > 0) {
            $wery->whereHas('cates', function($q) use($prd_cate_id) {
                $q->where('cate_id',$prd_cate_id);
            });
//            $wery->whereRaw("CONCAT('-',category_id,'-') like CONCAT('%-',".(int)$prd_cate_id.",'-%')");
        }

        $wery->where('type', '=', $type);
//        dd($wery->get());
        return $wery->get();
    }
    public static function getFilterCateByProID($pro_id, $cat_id){
        $data = \DB::table('filter_cates')
            ->join('filter_cate_pivot', 'filter_cate_pivot.filter_cate_id', '=', 'filter_cates.id')
            ->where('filter_cate_pivot.cate_id', $cat_id)
            ->join('filters', 'filters.filter_cate_id', '=', 'filter_cates.id')
            ->join('filter_details','filter_details.filter_id', '=', 'filters.id')
            ->where('filter_details.object_id', $pro_id)
            ->where('filter_details.for_price', 0)
            ->where('filter_cates.show_detail', '>', 0)
            ->select('filters.id')
            ->get()
            ->keyBy('id')
            ->toArray();
        return $data;
    }

    public static function getFilterCateByIds($ids = [],$type = 'product'){
        return self::where('type', '=', $type)
            ->whereIn('id',$ids)
            ->get();
    }

    public static function getFilterCatesAd($type = 'product'){
        return self::with(['filters' => function($q) {
            $q->where('status','>',-1);
        }])->where('type', '=', $type)
            ->get()->keyBy('id');
    }

    public static function getByPrdCate($prd_cate_id = 0,$type = 'product') {
        $wery = self::with(['filters' => function($q) {
            $q->where('status','>',-1);
        }]);
        $wery->where('status','>',1);
        $wery->where('cate_id', $prd_cate_id);
        $wery->where('type',$type);
//        $wery->whereRaw("CONCAT('-',category_id,'-') like CONCAT('%-',".(int)$prd_cate_id.",'-%')");
        return $wery->get();
    }

    public static function getFilterCateByCate($cate_id = 0, $type = 'product'){
//        dd($cate_id);
        $data = self::leftJoin('filter_cate_pivot','filter_cate_pivot.filter_cate_id', '=', 'filter_cates.id')
            ->where('filter_cate_pivot.cate_id', $cate_id)
            ->where('filter_cates.status', '>', 0)
            ->where('filter_cates.type', $type)
            ->select('filter_cates.id', 'filter_cates.title', 'filter_cate_pivot.filter_cate_id', 'filter_cates.show_filter', 'filter_cates.cate_id', 'filter_cates.show_filter_mobile', 'filter_cates.sort as sort' )
//            ->groupBy('sort');
            ->get()
            ->toArray();
        return $data;

    }

    public static function getByPrdCateMenu($type = 'product'){

        $wery = self::with(['filters' => function($q) {
            $q->where('status','>',-1);
        }]);
        $wery->where('status','>',0);
        $wery->where('show_menu', '>', 0);
        $wery->where('type',$type);
        return $wery->get();

    }

    public static function getAllFilter($type = 'product'){
        $wery = self::with(['filters' => function($q) {
            $q->where('status','>',-1);
        }]);
        $wery->where('status','>',1);
        $wery->where('type',$type);
//        $wery->whereRaw("CONCAT('-',category_id,'-') like CONCAT('%-',".(int)$prd_cate_id.",'-%')");

        return $wery->get();
    }

    public static function getProductByCate($id = 0){
        $data = \DB::table('filters')
            ->join('filter_cates', 'filter_cates.id', '=','filters.filter_cate_id')
            ->join('filter_details', 'filters.id', '=', 'filter_details.filter_id')
            ->join('products', 'products.id', '=', 'filter_details.object_id')
            ->select('filters.title', 'filters.id')
            ->where('filters.type', 'product');
        if($id > 0){
            $data = $data->where('products.id', '=', $id);
        }
        return $data->get()->toArray();
    }

    public function cates()
    {
        return $this->belongsToMany('App\Models\Category', 'filter_cate_pivot', 'filter_cate_id', 'cate_id');
    }

    public static function getFilterCate(){
        return self::with(['filters' => function($q){
            $q->where('status', '>', 0);
            $q->where('pid', 0);
            $q->where('is_far', 1);
        }])->where('status', '>', 0)->where('type', 'product')->get()->toArray();
    }
    public static function fetchResult($data)
    {
        $tmp = [];
        if(isset($data) && !empty($data)) {
            foreach ($data as $key => $item) {
                $tmp[$item['pid']][] = $item;

            }
            $tmp = \Lib::createTree($tmp, $tmp[0]);
        }
        return $tmp;
    }
}
