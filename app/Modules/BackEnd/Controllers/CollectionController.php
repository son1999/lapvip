<?php

namespace App\Modules\BackEnd\Controllers;

use App\Models\Collection;
use App\Models\FilterCate;
use App\Models\FilterDetail;
use App\Models\Category;
use Illuminate\Http\Request;

use App\Models\Filter;
use App\Models\Collection as THIS;

class CollectionController extends BackendController
{
    protected $timeStamp = 'created';

    //config controller, ez for copying and paste
    public function __construct(){
        $this->bladeAdd = 'add';
        parent::__construct(new THIS(), [
            [
                'title' => 'required|max:250',
                'cat_id' => 'required',
            ]
        ]);
        \View::share('catOpt', Category::getCat(1));
        \View::share('filter', FilterCate::getFilterCates('', ''));
        $this->registerAjax('get-collect', 'ajaxGetCollectionByCate');
    }

    public function index(Request $request){
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
            if ($request->cat_id != '') {
                $cond[] = ['cat_id', $request->cat_id];
            }
            if ($request->title != '') {
                $cond[] = ['title', 'LIKE', '%' . $request->title . '%'];
            }
            if(!empty($request->time_from)){
                $timeStamp = \Lib::getTimestampFromVNDate($request->time_from);
                array_push($cond, ['created', '>=', $timeStamp]);
            }
            if(!empty($request->time_to)){
                $timeStamp = \Lib::getTimestampFromVNDate($request->time_to, true);
                array_push($cond, ['created', '<=', $timeStamp]);
            }
        }
        if(!empty($cond)) {
            $data = THIS::with('cates')->where($cond)->orderByRaw('created DESC, id DESC')->paginate($this->recperpage);
        }else{
            $data = THIS::with('cates')->orderByRaw('created DESC, id DESC')->paginate($this->recperpage);
        }

        return $this->returnView('index', [
            'data' => $data,
            'search_data' => $request,
        ]);
    }
    public function showEditForm($id){
        $data = THIS::find($id);
        \View::share('catOpt', Category::getCat(1));
        $cates = \DB::table('collection_cate_pivot')->where('collect_id', $id)->select('cat_id as id')->get();
        return $this->returnView('edit', [
            'data' => $data,
            'cates' => json_decode($cates, true)

        ]);
    }

    public function beforeSave(Request $request, $ignore_ext = [])
    {
        parent::beforeSave($request, $ignore_ext); // TODO: Change the autogenerated stub

        unset($this->model->cat_id);
    }
    public function afterSave(Request $request){
        $cates = $request->cat_id;
        $this->model->cates()->sync($cates);
    }
    protected function ajaxGetCollectionByCate(Request $request){
        if($request->id > 0) {
            $collection = Collection::getByCateId($request->id);
            if(!empty($collection)) {
                return \Lib::ajaxRespond(true, 'success',[
                    'collection' => $collection,
                ]);
            }
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }


}