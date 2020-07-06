<?php

namespace App\Modules\BackEnd\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
class AjaxController extends BackendController
{
    public function __construct(){}

    public function init(Request $request, $cmd){
        $data = [];
        $perm = true;
        switch ($cmd) {
            case 'filterProductCollection':
                $data = $this->filterProductCollection($request);
                break;
            default:
                $data = $this->nothing();
        }
        if(!$perm) {
            $data = \Lib::ajaxRespond(false, 'Access denied');
        }
        return response()->json($data);
    }

    /*public function actived(Request $request){
        $user = \Auth::user();
        $user->last_active = time();
        $user->save();
        return \Lib::ajaxRespond(true, 'Actived');
    }*/

    public function nothing(){
        return "Nothing...";
    }

    function fetch_data(Request $request)
    {
        // dd($this->bladeAdd);
        if($request->ajax())
        {
            $data = Product::paginate(20);

            $html = \View::make('BackEnd::pages.collection.pagination_data')->render();
            // return $this->returnView('BackEnd::pages.collection.pagination_data',[ 
            //     'filter_cate_price' => [],
            //     'filter_cate_not_price' => [],
            //     'data' => $data,
            //     'search_data' => [], 
            // ])->render();
        }
    }

    public function filterProductCollection(Request $request){
        // dd('1');
        $cond = [];
        if($request->id != ''){
            $cond[] = ['id', $request->id];
        }else {
            if ($request->status != '') {
                $cond[] = ['status', $request->status];
            } else {
                $cond[] = ['status', '>', 0];
            }
            if ($request->lang != '') {
                $cond[] = ['lang', '=', $request->lang];
            }
            if ($request->title != '') {
                $cond[] = ['title', 'LIKE', '%' . $request->title . '%'];
            }
        }

        if(!empty($cond)) {
            $data = Product::where($cond)->orderByRaw('created DESC, id DESC')->paginate($this->recperpage);
        }else{
            $data = Product::orderByRaw('created DESC, id DESC')->paginate($this->recperpage);
        }
        $html = \View::make('BackEnd::pages.collection.pagination_data',['data' => $data])->render();
            echo $html;
    }
}
