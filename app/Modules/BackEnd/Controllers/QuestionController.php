<?php


namespace App\Modules\BackEnd\Controllers;


use App\Libs\LoadDynamicRouter;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Question as THIS;
use Illuminate\Support\Facades\Auth;

class QuestionController extends BackendController
{
    //config controller, ez for copying and paste
    protected $timeStamp = 'created';
    protected $foods_perpage = 5;

    public function __construct()
    {
        parent::__construct(new THIS());
        LoadDynamicRouter::loadRoutesFrom('FrontEnd');
        $this->registerAjax('status_question', 'ajaxStatusComment');
        $this->registerAjax('answer-question', 'ajaxAnswer');
    }

    public function index(Request $request)
    {
        $order = 'created DESC, id DESC';
        $cond = [];

        if ($request->status != '') {
            $cond[] = ['status', $request->status];
        } else {
            $cond[] = ['status', '>', 0];
        }
        if($request->title != ''){
            $cond[] = ['question','LIKE','%'.$request->title.'%'];
        }

        if(!empty($request->time_from)){
            $timeStamp = \Lib::getTimestampFromVNDate($request->time_from);
            array_push($cond, ['created', '>=', $timeStamp]);
        }
        if(!empty($request->time_to)){
            $timeStamp = \Lib::getTimestampFromVNDate($request->time_to, true);
            array_push($cond, ['created', '<=', $timeStamp]);
        }
        $data = THIS::where($cond)->where('qid', 0)->orderByRaw($order)->paginate($this->recperpage);
        if ($data){
            foreach ($data as $item){
                $ans = THIS::where('qid', '=', $item->id)->where('status', '>', 0)->whereNull('question')->get();
            }
        }
        // dd($data);
        return $this->returnView('index', [
            'data'           => $data,
            'ans'            => @$ans,
            'search_data'    => $request,

        ]);
    }

    public function view($id)
    {
        $pro = THIS::with('product')->where('id', $id)->first();
//        dd($pro->product['id']);
        $data = THIS::where('id', $id)->first();
//        dd($data);
        $title = 'Chi tiết câu hỏi';
        return $this->returnView('view', [
            'site_title'       => $title,
            'data'             => $data,
            'pro'              => $pro
        ], $title);
    }

    public function ajaxStatusComment(Request $request){

        if($request->id > 0) {
            $data = $this->model::where('id', $request->id)->first();
            if ($data) {
//                if ($data->answer != '' && $data->aid != ''){
                    $before = $data->status;
                    $data->status = $before == 1 ? 2 : 1;
                    $data->save();
                    \MyLog::do()->add($this->key.'-status_question', $data->id, $data->status, $before);
                    return \Lib::ajaxRespond(true, 'success');
//                }
//                return \Lib::ajaxRespond(false, 'Bạn chưa trả lời câu hỏi của người dùng! Không thể hiển thị');
            }
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }

    public function ajaxAnswer(Request $request){
        if (!empty($request->id)) {
            $check = $this->model::where('id', $request->id)->first();
            $data = $this->model;
            $data->aid = \Auth::user()->id;
            $data->answer = $request->data;
            $data->product_id = $request->proid;
            $data->name = \Auth::user()->fullname;
            $data->status = 1;
            $data->created = strtotime(now());
            $data->qid = $request->id;
            $data->save();
            \MyLog::do()->add($this->key.'-answer-question', $data->id, $data);
            return \Lib::ajaxRespond(true, 'success', $data->id);

        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }
}