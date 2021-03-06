<?php

namespace App\Modules\BackEnd\Controllers;

use Illuminate\Http\Request;

use App\Models\Page as THIS;

class StaticPageController extends BackendController
{
    protected $timeStamp = 'created';

    //config controller, ez for copying and paste
    public function __construct(){
        $this->bladeAdd = 'add';
        parent::__construct(new THIS(), [
            [
                'title' => 'required|max:250',
                'title_seo' => 'max:250',
                'body' => 'required|min:50',
            ]
        ]);

        \View::share('type', THIS::$type);
        $this->registerAjax('showHome', 'ajaxShowHome');
    }

    public function index(Request $request){
        $order = 'created DESC, id DESC';
        $cond = [];
        if ($request->status != '') {
            $cond[] = ['status', $request->status];
        } else {
            $cond[] = ['status', '>', 0];
        }
        if($request->lang != ''){
            $cond[] = ['lang','=',$request->lang];
        }
        if($request->title != ''){
            $cond[] = ['title','LIKE','%'.$request->title.'%'];
        }
        if(!empty($request->time_from)){
            $timeStamp = \Lib::getTimestampFromVNDate($request->time_from);
            array_push($cond, ['created', '>=', $timeStamp]);
        }
        if(!empty($request->time_to)){
            $timeStamp = \Lib::getTimestampFromVNDate($request->time_to, true);
            array_push($cond, ['created', '<=', $timeStamp]);
        }
        if(!empty($cond)) {
            $data = THIS::where($cond)->orderByRaw($order)->paginate($this->recperpage);
        }else{
            $data = THIS::orderByRaw($order)->paginate($this->recperpage);
        }
        return $this->returnView('index', [
            'data' => $data,
            'search_data' => $request
        ]);
    }

    public function beforeSave(Request $request, $ignore_ext = [])
    {
        parent::beforeSave($request); // TODO: Change the autogenerated stub
        if(empty($this->model->title_seo)){
            $this->model->title_seo = $this->model->title;
        }
        if(empty($this->model->link_seo)){
            $this->model->link_seo = $request->alias;
        }
        if(empty($this->model->sort)){
            $this->model->sort = 0;
        }
        //xoa bien
        unset($this->model->uploadify);
    }

    public  function ajaxShowHome(Request $request){
        if($request->id > 0) {
            $data = $this->model::find($request->id);
            if ($data) {
                $before = $data->show_home;
                $data->show_home = $request->show == 1 ? 1 : 0;
                $data->save();
                \MyLog::do()->add($this->key.'-showHome', $data->id, $data->show_home, $before);
                return \Lib::ajaxRespond(true, 'success');
            }
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }
}
