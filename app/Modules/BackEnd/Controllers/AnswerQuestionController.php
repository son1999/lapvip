<?php


namespace App\Modules\BackEnd\Controllers;


use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\AnswerQuestion as THIS;
use Illuminate\Support\Facades\Auth;

class AnswerQuestionController extends BackendController
{
    //config controller, ez for copying and paste
    protected $timeStamp = 'created';
    protected $foods_perpage = 5;

    public function __construct()
    {
        parent::__construct(new THIS());
        $this->registerAjax('status_question', 'ajaxStatusComment');
        $this->registerAjax('answer-question-installment', 'ajaxAnswerInstallment');
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
        $data = THIS::where($cond)->orderByRaw($order)->paginate($this->recperpage);
        // dd($data);
        return $this->returnView('index', [
            'data'           => $data,
            'search_data'    => $request,

        ]);
    }

    public function view($id)
    {
        $pro = THIS::with('product')->where('id', $id)->first();
//        dd($pro->product['id']);
        $data = THIS::with('user')->where('id', $id)->first();

        $answer = THIS::where('qid', $data->id)->where('status', 1)->get();
        $title = 'Chi tiết câu hỏi';
        return $this->returnView('view', [
            'site_title'       => $title,
            'data'             => $data,
            'pro'              => $pro,
            'answer'           => $answer,
        ], $title);
    }

    public function ajaxStatusComment(Request $request){

        if($request->id > 0) {
            $data = $this->model::where('id', $request->id)->first();
            if ($data) {
                if ($data->answer != '' && $data->aid != ''){
                    $before = $data->status;
                    $data->status = $before == 1 ? 2 : 1;
                    $data->save();
                    \MyLog::do()->add($this->key.'-status_question', $data->id, $data->status, $before);
                    return \Lib::ajaxRespond(true, 'success');
                }
                return \Lib::ajaxRespond(false, 'Bạn chưa trả lời câu hỏi của người dùng! Không thể hiển thị');
            }
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }

    public function ajaxAnswerInstallment(Request $request){
        if (!empty($request->id)) {
            $check = $this->model::where('id', $request->id)->first();
            $data = $this->model;
            $data->uid = \Auth::user()->id;
            $data->answer = $request->data;
            $data->name = \Auth::user()->fullname;
            $data->status = 1;
            $data->created = strtotime(now());
            $data->qid = $request->id;
            $data->save();
            \MyLog::do()->add($this->key.'-answer-question-installment', $data->id, $data);
            return \Lib::ajaxRespond(true, 'success', $data->id);

        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }

}