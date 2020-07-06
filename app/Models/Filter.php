<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Filter extends Model
{
    //
    protected $table = 'filters';
    public $timestamps = false;
    const TYPE = [
        'product' => 'product'
    ];

    public function filter_details() {
        return $this->hasMany('App\Models\FilterDetail','filter_id','id');
    }

    
    public function filter_cate() {
        return $this->belongsTo('App\Models\FilterCate','filter_cate_id','id');
    }
    public static function getFiltecate($fil_cate){
        $wery = \DB::table('filter_cates');
//        if ($pid != 0){
//            $wery->join('filters', 'filters.filter_cate_id', '=', 'filter_cates.id');
//            $wery->where('filters.id', $pid);
//            $wery->select('filters.title as title_fil');
//        }
        $wery->where('filter_cates.id', $fil_cate);
        $wery->select('filter_cates.title');
        return $wery->first();
    }
    public static function getFilterByID($pid){
        $wery = \DB::table('filters');
        $wery->where('filters.id', $pid);
        $wery->select('filters.title as title_fil');
        return $wery->first();
    }
    public static function getFilters($type = 'product'){
        return self::where('type', '=', $type)->where('status', '>', -1)
            ->get()
            ->toArray();
    }

    public static function getWithCate($ids = [], $type='product')
    {
        if(!empty($ids)) {
            return self::with('filter_cate')
                ->where('type', '=', $type)
                ->whereIn('id', $ids)
                ->select('id', 'title', 'filter_cate_id')
                ->get();
        }
        return false;
    }

    public static function getByFilterIds($ids = [],$type = 'product')
    {
        return self::where('type', '=', $type)
            ->whereIn('id',$ids)
            ->select('id','title', 'filter_cate_id', 'image')
            ->get();
    }

    public static function getProductFilters($id = 0,$first = false){
        $data = \DB::table('filters')
            ->join('filter_details', 'filters.id', '=', 'filter_details.filter_id')
            ->join('products', 'products.id', '=', 'filter_details.object_id')
            ->select('filters.title', 'filters.id')
            ->where('filters.type', 'product');
        if($id > 0){
            $data = $data->where('products.id', '=', $id);
        }
        if($first){
            return $data->first();
        }else {
            return $data->get()->toArray();
        }
    }
    public function getImageUrl($size = 'original'){
        return \ImageURL::getImageUrl($this->image, 'filters', $size);
    }

    

    public static function addFilters($filters, $type, $id){
        //lay thong tin tag
        $filters = explode(',', $filters);
        foreach ($filters as $k => $v){
            $filters[$k] = str_slug($v);
        }
        $filters = self::select('id')
            ->where('type', '=', $type)
            ->whereIn('safe_title', $filters)
            ->get()->toArray();
        if(!empty($filters)) {
            $insertData = [];
            foreach ($filters as $item) {
                $insertData[] = [
                    'object_id' => $id,
                    'filter_id' => $item['id'],
                    'type' => $type
                ];
            }
            if (!empty($insertData)) {
                //xoa het tag cu
                FilterDetail::where('object_id', $id)
                    ->where('type', $type)
                    ->delete();
                //chen moi
                FilterDetail::insert($insertData);

                return true;
            }
        }
        return false;
    }

    public static function getFilterByFilterCateID($arr_cate_id){
        $data = self::where('status', '>', -1)
            ->whereIn('filter_cate_id', $arr_cate_id)
            ->orderBy('id', 'ASC')
            ->get()
            ->toArray();
        return $data;
    }

    public static function getFiltersByArrID($Arr_filter = []){
        return self::where('status', '>', 0)->whereIn('id', $Arr_filter)->select('id', 'filter_cate_id')->get()->toArray();
    }
    public static function getFilterFar($fil_cate){
        return self::where('status', '>', 0)->where('pid', 0)->where('is_far', 1)->where('filter_cate_id', $fil_cate)->get();
    }
}
