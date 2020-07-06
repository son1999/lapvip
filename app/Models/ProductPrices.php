<?php
/**
 * Created by PhpStorm.
 * Filename: ProductPrices.php
 * User: Thang Nguyen Nhan
 * Date: 20-Jul-19
 * Time: 01:13
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class ProductPrices extends Model
{
    protected $table = 'product_prices';
    public $timestamps = false;

    protected $appends = [
        'filters'
    ];

    public function storage()
    {
        return $this->hasMany('App\Models\Storage','prd_price_id','id');
    }

    public function product() {
        return $this->hasOne("\App\Models\Product",'id','product_id');
    }

    public static function getByPrdId($id,$for_admin = false)
    {
//        $wery = DB::table('product_prices');
//        $wery->join('filters',function($join) {
//            $join->on(DB::raw('filters.id in (product_prices.filter_ids)'),DB::raw(''),DB::raw(''));
//        });
//        $wery->select('product_prices.*');
//        $wery->where('product_id',$id);
//        $wery->groupBy('filters.id');
//        dd($wery->toSql());

        $sql = "select `product_prices`.* 
                    from `product_prices` inner join `filters` on filters.id in (product_prices.filter_ids) 
                    where `product_id` = :prd_id ";
        if(!$for_admin) {
//            $sql .= "and `quantity` > 0";
        }
//        $sql .= " group by `filters`.`id`";

        $wery = DB::select(DB::raw($sql),
            ['prd_id' => $id]
        );
        $arr_filters = [];
        $arr_prd_price_ids = [];
        foreach($wery as $item) {
            $temp = explode(',',$item->filter_ids);
            $arr_filters = array_merge($temp,$arr_filters);
            $item->filter_ids_arr = $temp;
            $arr_prd_price_ids[] = $item->id;
//            $item->filters = self::getFilters($temp);
        }
        $filters = Filter::getByFilterIds(array_unique($arr_filters))->keyBy('id')->toArray();

        $storage = Storage::getByPriceIds($arr_prd_price_ids)->toArray();

        if(!$for_admin) {
            $arr_fil_cate = [];
            foreach ($filters as $item) {
                $arr_fil_cate[] = $item['filter_cate_id'];
            }
            $filter_cates = FilterCate::getFilterCateByIds(array_unique($arr_fil_cate))->keyBy('id')->toArray();
        }

        foreach($wery as &$item) {
            $item->storage = [];
            if(!empty($item->filter_ids_arr)) {
                foreach ($item->filter_ids_arr as $it) {
                    if (isset($filters[$it])) {
                        $item->filters[] = $filters[$it];
                    }
                }
            }else {
                $item->filters[] = [];
            }

            foreach($storage as $itm_stora) {
                if($itm_stora['prd_price_id'] == $item->id) {
                    $item->storage[] = $itm_stora;
                }
            }
        }

        $data_return['prices'] = collect($wery)->toArray();
        if(!$for_admin) {
            $data_return['filters'] = @$filters;
            $data_return['filter_cates'] = @$filter_cates ?? [];
        }
//dd($data_return);
        return $data_return;
    }

    public function getFiltersAttribute(){
        $wery = Filter::whereIn('id',explode(',',$this->filter_ids));
        return $wery->get();
    }
}