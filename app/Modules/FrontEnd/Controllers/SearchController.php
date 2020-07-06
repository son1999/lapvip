<?php

namespace App\Modules\FrontEnd\Controllers;

use App\Models\FilterCate;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Libs\Cart;

class SearchController extends Controller
{
    protected $recperpage = 9;
    public function __construct(){

    }

    public function searchKey(Request $request){
//        dd(Product::$orderClauseText);
//            $cond = [];
//            if($request->key != ''){
//                $cond[] = ['title','LIKE','%'.$request->key.'%'];
//            }
            $sort_by = isset($request->sort_by) ? $request->sort_by : 3;
//            $list_products = Product::where('status', 2)->where($cond)->get();
            return view('FrontEnd::pages.product.searchKey', [
                'site_title' => 'Tìm kiếm',
                'sort_by' => $sort_by,
                'orderClauseText' => Product::$orderClauseText,
                'list_products' => Product::getByTitle($this->recperpage,$request->key,$sort_by),
            ]);

        abort(404);
    }
}
