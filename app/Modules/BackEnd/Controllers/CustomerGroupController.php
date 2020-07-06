<?php

namespace App\Modules\BackEnd\Controllers;

use App\Models\CustomerGroupDetail;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Http\Request;

use App\Models\CustomerGroup as THIS;

class CustomerGroupController extends BackendController
{
    protected $timeStamp = 'created';

    public function __construct(){
        parent::__construct(new THIS(),[[
            'title' => 'required|max:250',
        ]]);

        \View::share('layers', THIS::LAYER_GROUP_CUSTOMER);
    }

    public function index(Request $request){
        $cond = ['status > 0'];
        if($request->title != ''){
            $cond[] = 'title LIKE %'.$request->title.'%';
        }
        $data = THIS::where(\Lib::condition($cond))
            ->orderByRaw('sort DESC, created DESC')
            ->paginate($this->recperpage);
        $details = CustomerGroupDetail::with(['hotel', 'room'])->whereIn('customer_group_id', $data->pluck('id'))->get();

        return $this->returnView('index', [
            'data' => $data,
            'details' => $details,
            'search_data' => $request,
        ]);
    }

    public function showEditForm($id){
        $data = THIS::find($id);
        set_old($data);
        if($data) {
            $title = 'Sửa thông tin';
            \Lib::addBreadcrumb($title);
            return $this->returnView('edit', [
                'data' => $data,
            ]);
        }
        return $this->notfound();
    }

    public function buildValidate(Request $request){
        switch($request->type){
            case 'percent':
                $this->addValidate(['percent' => 'required|numeric']);
                break;
            case 'bonus':
                $this->addValidate(['bonus' => 'required|numeric']);
                break;
            case 'other':
                break;
        }
    }

    public function beforeSave(Request $request, $ignore_ext = []){
        parent::beforeSave($request, ['type', 'except', 'hotel_id', 'room_id', 'number', 'ftype', 'layer', 'fee_payment', 'fee_transfer', 'fee_cod', 'fee_office', 'revenue', 'roomnight','rating_period']);
        $this->model->percent = 0;
        $this->model->bonus = 0;
        $this->model->other = !empty($request->except) ? 1 : 0;
        switch($request->type){
            case 'percent':
                $this->model->percent = $request->percent;
                break;
            case 'bonus':
                $this->model->bonus = $request->bonus;
                break;
        }

        $this->model->show = !empty($request->show) ? $request->show : 0;

//        $this->model->payment_fee = json_encode([
//            'fee_payment' => $request->fee_payment,
//            'fee_transfer' => intval(str_replace(',', '', $request->fee_transfer)),
//            'fee_cod' => intval(str_replace(',', '', $request->fee_cod)),
//            'fee_office' => intval(str_replace(',', '', $request->fee_office)),
//        ]);

        $this->model->type = $request->layer;
//        $this->model->saveEmails($request->emails);
    }

    public function afterSave(Request $request)
    {
        if (!empty($request->ftype)) {
            if ($this->editMode) {
                CustomerGroupDetail::where('customer_group_id', $this->model->id)->delete();
            }
            //them moi
            foreach ($request->ftype as $k => $v){
                $detail = new CustomerGroupDetail();
                $detail->customer_group_id = $this->model->id;
                if($v == 'percent'){
                    $detail->percent = $request->number[$k];
                }else{
                    $detail->bonus = $request->number[$k];
                }
                $detail->created = time();
                $detail->save();
            }
        }
    }

    public function beforeDelete($item)
    {
        if($item->id == 1){
            $this->setError(['no_access' => 'Không được xóa nhóm mặc định']);
        }
    }
}
