<?php

namespace App\Modules\BackEnd\Controllers;

use Illuminate\Http\Request;

use App\Models\InstagramBox as THIS;

class InstagramController extends BackendController
{

    //config controller, ez for copying and paste
    public function __construct(){
        parent::__construct(new THIS(),[
            [
                'title' => 'required|max:250',
                'link' => 'nullable|url',
                'embed_code' => 'nullable',
            ]
        ]);

    }

    public function index(Request $request){
        $order = 'created_at DESC, id DESC';
        $cond = [['status','>',0]];
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

        $this->model->status = !empty($request->status) ? $request->status : 1;
    }
}
