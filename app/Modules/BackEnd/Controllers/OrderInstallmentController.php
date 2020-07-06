<?php

namespace App\Modules\BackEnd\Controllers;

use App\Exports\OrderInstallmentExport;
use App\Libs\LoadDynamicRouter;
use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;

use App\Models\InstallmentSuccess as THIS;

class OrderInstallmentController extends BackendController
{
    //config controller, ez for copying and paste
    protected $timeStamp = 'created';
    protected $titleField = 'code';
    protected $foods_perpage = 5;

    public function __construct()
    {

        parent::__construct(new THIS());
        $this->registerAjax('updateProcessing', 'ajaxProcessing');
        $this->registerAjax('updateProgress', 'ajaxProgress');
        $this->registerAjax('updateFinish', 'ajaxFinish');
    }
    public function index(Request $request){
        $order = 'created DESC, id DESC';
        $cond = [['status',\request()->status]];


        if(!empty($cond)) {
            $data = THIS::with(['product' => function($query){
                $query->select('id', 'title');
            }, 'filters' =>function($query){
                $query->select('id', 'title', 'filter_cate_id');
            }, 'filters.filter_cate' => function($query){
                $query->select('id', 'title');
            }, 'warehouse'])->where($cond)->orderByRaw($order)->paginate($this->recperpage);
        }else{
            $data = THIS::with(['product' => function($query){
                $query->select('id', 'title');
            }, 'filters' =>function($query){
                $query->select('id', 'title');
            }, 'filters.filter_cate' => function($query){
                $query->select('id', 'title');
            }, 'warehouse' =>function($query){
                $query->select('id', 'title');
            }])->orderByRaw($order)->paginate($this->recperpage);
        }
        return $this->returnView('index', [
            'data' => $data,
            'search_data' => $request
        ]);
    }

    public function view($id){
        LoadDynamicRouter::loadRoutesFrom('FrontEnd');
        $data = THIS::getOrderById($id);
        $title = 'Xem đơn hàng';
        return $this->returnView('view', [
            'site_title'       => $title,
            'data'             => $data,
        ], $title);
    }


    protected function ajaxProcessing(Request $request){
        if($request->id > 0) {
            $data = THIS::find($request->id);
            if ($data) {
                $before = $data->status;
                $data->status = $request->status == 1 ? 1 : 0;
                $data->save();
                \MyLog::do()->add('installment-processing', $data->id, $data->status, $before);
                return \Lib::ajaxRespond(true, 'success');
            }
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }
    protected function ajaxProgress(Request $request){
        if($request->id > 0) {
            $data = THIS::find($request->id);
            if ($data) {
                $before = $data->status;
                $data->status = $request->status == 2 ? 2 : 1;
                $data->save();
                \MyLog::do()->add('installment-Progress', $data->id, $data->status, $before);
                return \Lib::ajaxRespond(true, 'success');
            }
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }
    protected function ajaxFinish(Request $request){
        if($request->id > 0) {
            $data = THIS::find($request->id);
            if ($data) {
                $before = $data->status;
                $data->status = $request->status == 3 ? 3 : 2;
                $data->save();
                \MyLog::do()->add('installment-Finish', $data->id, $data->status, $before);
                return \Lib::ajaxRespond(true, 'success');
            }
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }

    public function export(){
        return Excel::download(new OrderInstallmentExport, 'OrderInstallment.xlsx');
    }

}