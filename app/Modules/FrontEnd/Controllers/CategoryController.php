<?php

namespace App\Modules\FrontEnd\Controllers;

use App\Models\FilterCate;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Libs\Cart;

class CategoryController extends Controller
{
    protected $recperpage = 9;
    public function __construct(){
//        dd(Category::getCat(1));
        \View::share('cates', Category::getCat(1));
        \Lib::addBreadcrumb();
    }

    public function index(Request $request){
        \Lib::addBreadcrumb('Sản phẩm',route('product.list'));
        $sort_by = $request->sort_by;
        $khoiluong = $request->khoiluong;
        $khoiluong = $khoiluong ? explode(',',$khoiluong) : '';

//            dd(Product::getByCate($id,$this->recperpage,explode(',',@$khoiluong)));
        return view('FrontEnd::pages.product.category', [
            'site_title' => 'Sản phẩm',
            'khoiluong' => $khoiluong,
            'sort_by' => $sort_by,
            'products' =>  Product::getByCate(0,$this->recperpage, $khoiluong,'',$sort_by),
            'filter_cates' => FilterCate::getFilterCates(),
            'type' => 'menu_detail'
        ]);
    }

    public function detail($safe_title, $id,Request $request){
        $cat = Category::getCateById($id,1,true);
        if($cat){
            \Lib::addBreadcrumb($cat->title);
            if ($cat->lang != \Lib::getDefaultLang()) {
                return redirect()->route('menu');
            }

            $filter_cate = FilterCate::getByPrdCate($id);
//            dd(json_encode($filter_cate));
//            dd($filter_cate);

            $sort_by = isset($request->sort_by) ? $request->sort_by : 3;
            $filter_ids = $request->filter_ids;
            $filter_ids = $filter_ids ? explode(',',$filter_ids) : '';
            $choosed_filters = [];
            if(!empty($filter_ids)) {
                foreach ($filter_cate as $cate) {
                    foreach ($cate->filters as $filter) {
                        if (in_array($filter->id, $filter_ids)) {
                            $choosed_filters[] = [
                                'cate_title' => $cate->title,
                                'cate_id' => $cate->id,
                                'filter_title' => $filter->title,
                                'filter_id' => $filter->id
                            ];
                        }
                    }
                }
            }

//            dd(Product::getByCate($id,$this->recperpage, $filter_ids,'',$sort_by));
            return view('FrontEnd::pages.product.category', [
                'site_title' => $cat->title,
                'filter_cate' => $filter_cate,
                'choosed_filters' => $choosed_filters ?? [],
                'sort_by' => $sort_by,
                'orderClauseText' => Product::$orderClauseText,
                'products' =>  Product::getByCate($id,$this->recperpage, $filter_ids,'',$sort_by),
                'current_cate' => $cat,
                'filter_cates' => FilterCate::getFilterCates(),
                'type' => 'menu_detail'
            ]);
        }
        abort(404);
    }
}
