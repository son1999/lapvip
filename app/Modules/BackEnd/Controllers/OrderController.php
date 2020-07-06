<?php

namespace App\Modules\BackEnd\Controllers;

use App\Libs\BizPay;
use App\Libs\LoadDynamicRouter;
use App\Libs\WePayAPI;
use App\Models\Customer;
use App\Models\GeoDistrict;
use App\Models\Order;
use App\Models\OrderLog;
use DB;
use Validator;
use App\Models\GeoProvince;
use App\Models\User;
use Illuminate\Http\Request;

use App\Models\Order as THIS;

class OrderController extends BackendController
{
    //config controller, ez for copying and paste
    protected $timeStamp = 'created';
    protected $titleField = 'code';
    protected $foods_perpage = 5;

    public function __construct()
    {
        $rules = [
            'fullname' => 'required|max:250',
            'phone' => ['required','numeric','regex:/^(03|09|01[2|6|8|9])+([0-9]{8})$/']
        ];
        if(!(\Route::current()->getActionMethod() == 'showEditForm')) {
//            $rules['quantity'] =  'required';
//            $rules['adult'] =  ['required','numeric','min:1'];
//            $rules['child'] = ['numeric','min:0'];
//            $rules['booking_date'] = ['required','regex:/^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$/'];
//            $rules['booking_time'] = ['required','regex:/^([0|1]{0,}[0-9]|[2][0-3]):([0-5][0-9])\s(AM|PM)$/'];
        }
        parent::__construct(new THIS(),[
            $rules,
            [
                'fullname.required' => 'Hãy nhập tên khách hàng',
                'phone.required' => 'Hãy nhập số điện thoại',
                'phone.regex' => 'Số điện thoại không hợp lệ',
                'phone.numeric' => 'Số điện thoại không hợp lệ',
//                'quantity.required' => 'Hãy thêm món ăn vào đơn',
//                'adult.required' => 'Hãy nhập số lượng người lớn',
//                'adult.min' => 'Tối thiểu phải có 1 người lớn',
//                'adult.numeric' => 'Hãy nhập số và ô người lớn',
//                'child.numeric' => 'Hãy nhập số và ô trẻ em',
//                'booking_date.required' => 'Hãy nhập ngày đặt',
//                'booking_time.required' => 'Hãy nhập thời gian đặt',
//                'booking_date.regex' => 'Ngày đặt không đúng định dạng',
//                'booking_time.regex' => 'Thời gian đặt không đúng định dạng',
            ]
        ]);

        if($this->form == 'list') {
            \View::share('users', User::select('id', 'user_name', 'fullname')->get());
            \View::share('bookingType', THIS::listType());
        }else{
            \View::share('cities', GeoProvince::getListProvinces());
        }

        LoadDynamicRouter::loadRoutesFrom('FrontEnd');
        $this->registerAjax('assign', 'ajaxAssignOrder4User', 'edit');
        $this->registerAjax('confirm', 'ajaxConfirmOrder', 'edit');
        $this->registerAjax('confirm_pending_paid', 'ajaxConfirmOrderPendingPaid', 'edit');
        $this->registerAjax('confirm_paid', 'ajaxConfirmOrderPaid', 'edit');
        $this->registerAjax('confirm_transport', 'ajaxConfirmOrderTransport', 'edit');
        $this->registerAjax('confirm_delivered', 'ajaxConfirmOrderDelivered', 'edit');
        $this->registerAjax('refund', 'ajaxRequireRefundOrder', 'edit');
        $this->registerAjax('cancel-refund', 'ajaxCancelRefundOrder', 'edit');
        $this->registerAjax('confirm-refund', 'ajaxConfirmRefundOrder', 'refund');
        $this->registerAjax('done-refund', 'ajaxDoneRefundOrder', 'refund');
        $this->registerAjax('cancel', 'ajaxConfirmOrderCancel', 'delete');
        $this->registerAjax('wepay-order', 'ajaxWepayOrder');
        $this->registerAjax('nganluong-order', 'ajaxNganLuonOrder');
        $this->registerAjax('bizpay-order', 'ajaxBizPayOrder');
    }

    public function index(Request $request)
    {
        $cond = [];
        if ($request->status != '') {
            if ($request->status == -99) {
                $cond[] = ['status', '=', 1];
            } elseif ($request->status == 1) {
                if ($request->receive == 1){
                    $cond[] = ['user_id', '!=', 0];
                }else{
                    $cond[] = ['user_id', '=', 0];
                }
                $cond[] = ['status', '=', 1];
            } else if ($request->status == 1000) {
                $condOr[] = [['status', '=', 4], ['status', '=', 2]];
            }else if ($request->status == 3) {
                $condOr[] = [['status', '=', 3], ['status', '=', 5]];
            } else if ($request->status == 5) {
                $condOr[] = [['status', '=', 5], ['status', '=', 6], ['status', '=', 7]];
            } else {
                $cond[] = ['status', '=', $request->status];
            }
        } else {
            $request->status = 1;
            $cond[] = ['status', '=', 1];
        }
        if ($request->code != '') {
            $cond[] = ['code', '=', $request->code];
        } else {
            if ($request->type != '') {
                $cond[] = ['type', '=', $request->type];
            }
            if ($request->phone != '') {
                $cond[] = ['phone', 'LIKE', '%' . $request->phone . '%'];
            }
            if ($request->email != '') {
                $cond[] = ['email', 'LIKE', '%' . $request->email . '%'];
            }
            if ($request->fullname != '') {
                $cond[] = ['fullname', 'LIKE', '%' . $request->customer_name . '%'];
            }
            if ($request->user_id != '') {
                $cond[] = ['user_id', '=', $request->user_id];
            }

            if (!empty($request->fromTime)) {
                array_push($cond, ['created', '>=', \Lib::getTimestampFromVNDate($request->fromTime)]);
            }
            if (!empty($request->toTime)) {
                array_push($cond, ['created', '<=', \Lib::getTimestampFromVNDate($request->toTime, true)]);
            }
        }

        $data = THIS::with('items','user');
        if (isset($condOr) && !empty($condOr)) {
            foreach ($condOr as $itm) {
                $data->where(function ($q) use ($itm) {
                    $first = true;
                    foreach ($itm as $eachCond) {
                        if ($first) {
                            $q->where([$eachCond]);
                            $first = false;
                        } else {
                            $q->orWhere([$eachCond]);
                        }
                    }
                });
            }
        }
        $data->where($cond);
        $data->orderByRaw('created DESC');
        return $this->returnView('index', [
            'data'           => $data->paginate($this->recperpage),
            'search_data'    => $request,
            'booking_status' => Order::listStatus(),
            'type'           => Order::listType()
        ],'');
    }

    public function view($id)
    {
        $data = THIS::getOrderById($id);
        $title = 'Xem đơn hàng';
        return $this->returnView('view', [
            'site_title'       => $title,
            'data'             => $data,
//            'shipping_notices' => Order::shippingNotices(),
        ], $title);
    }

    public function log(Request $request, $id)
    {
        $order = THIS::find($id);
        if ($order) {
            $data = OrderLog::where('order_id', $id);
            if (!empty($request->time_from)) {
                $timeStamp = \Lib::getTimestampFromVNDate($request->time_from);
                $data = $data->where('created', '>=', $timeStamp);
            }
            if (!empty($request->time_to)) {
                $timeStamp = \Lib::getTimestampFromVNDate($request->time_to, true);
                $data = $data->where('created', '<=', $timeStamp);
            }
            $data = $data->orderByRaw('created DESC')->paginate($this->recperpage);
            $title = 'Xem log đơn hàng';
            return $this->returnView('log', [
                'site_title'  => $title,
                'order'       => $order,
                'data'        => $data,
                'search_data' => $request
            ], $title);
        }
        return $this->notfound();
    }

    public function showEditForm($id){
        return $this->returnView('edit', [
            'data' => THIS::find($id),
//            'tags' => $tags
        ],'');
    }

    public function add_booking($type = 'order') {
        if($type == 'table') {
            return $this->returnView('add_table', [
                'type_order' => $type
            ],'Thêm đơn đặt bàn');
        }elseif($type == 'order') {
//            dd(Food::getForAdminChoose('aaaa',0,$this->foods_perpage));
            return $this->returnView('add_order', [
                'foods' => Product::getForAdminChoose('',0,$this->foods_perpage),
                'list_districts' => GeoDistrict::getListDistrictsByCity(1),// Mặc định là HÀ NỘI'payment_types' => Booking::paymentType(),
                'shipping_notices' => Order::shippingNotices(),
                'type_address' => AddressBook::getType(),
//                'bank_accounts' => Bank::getBankByLang(\Lib::getDefaultLang()),
                'payment_types' => Order::paymentType(),
                'max_quantity' => \Lib::getSiteConfig('max_quantity'),
            ],'Thêm đơn món ăn');
        }
        abort(404);
    }

    public function save_booking(Request $request,$type = 'order') {
        switch($type){
            case 'table':
                $this->model->startTime = \Lib::getTimestampFromVNDate($request->booking_date.' '.$request->booking_time);
                $now = time();
                if($this->model->startTime <= $now) {
                    if(isset($request->ajax) && $request->ajax == 1) {
                        return \Lib::ajaxRespond(false, __('site.thoigiandatbanphaisauthoidiemhientai'));
                    }else {
                        $validator = Validator::make(
                            array('tmp' => $this->model->startTime),
                            array('tmp' => 'min:'.$now.''),
                            ['tmp.min' => __('site.thoigiandatbanphaisauthoidiemhientai')]
                        );
                        if ($validator->fails()) {
                            return redirect(route('admin.order.add_booking', ['type' => $type]))
                                ->withErrors($validator)
                                ->withInput();
                        }
                    }
                }
                $this->model->type = 'table';
                $this->ignoreValidate('quantity');
                break;
            case 'order':
                if(isset($request->quantity)) {
                    foreach ($request->quantity as $k => $v) {
                        if ($v <= 0) {
                            $validator = Validator::make(
                                array('tmp' => $v),
                                array('tmp' => 'required|min:1|numeric'),
                                ['tmp.min' => __('site.soluongsanphamkhonghople')]
                            );
                            if ($validator->fails()) {
                                return redirect(route('admin.order.add_booking', ['type' => $type]))
                                    ->withErrors($validator)
                                    ->withInput();
                            }
                            break;
                        }
                        else if ($v > 100) {
                            $validator = Validator::make(
                                array('tmp' => $v),
                                array('tmp' => 'required|max:'.'100'.'|numeric'),
                                ['tmp.max' => __('site.soluongvuotqua').' '.'100']
                            );
                            if ($validator->fails()) {
                                return redirect(route('admin.order.add_booking', ['type' => $type]))
                                    ->withErrors($validator)
                                    ->withInput();
                            }
                            break;
                        }
                    }
                }
                $this->model->type = 'order';
                $this->ignoreValidate(['adult','child','booking_date','booking_time']);
                break;
        }

        return $this->save($request);
    }

    public function beforeSave(Request $request,$ignore_ext = [])
    {
        parent::beforeSave($request); // TODO: Change the autogenerated stub

        switch($this->model->type) {
            case 'table':
                unset($this->model->lang);
                unset($this->model->booking_date);unset($this->model->booking_time);
                break;
            case 'order':
                $this->model->province_id = 1;// Mặc đinh là Hà Nội
                unset($this->model->lang);
                unset($this->model->quantity);
                break;
        }
    }

    public function afterSave(Request $request)
    {
        if($this->editMode) {

        }else {
            switch($this->model->type) {
                case 'table':
                    break;
                case 'order':
                    $this->saveBookingItems($request);
                    break;
            }

            $this->model->code = Order::makeCode($this->model->type,$this->model->id,$request->lang ?? 'vi');
            $this->model->token_tracking = Order::genTokenTracking($this->model->code);
            $this->model->save();

            if($this->model->email != '') {
                event('customer.completecheckout', $this->model, 'order');
            }
        }
    }

    /*Gan Đang giao hang cho đơn*/
    protected function ajaxConfirmOrderTransport(Request $request)
    {
        if (\Auth::user() && $request->id > 0) {
            $order = Order::where('id', $request->id)->update([
                'updated' => time(),
                'status_for_cus' => 2,
            ]);
            OrderLog::add($request->id,'confirm_transport');
            return \Lib::ajaxRespond(true, 'success');
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }

    /*Gan Giao thanh cong cho don*/
    protected function ajaxConfirmOrderDelivered(Request $request)
    {
        if (\Auth::user() && $request->id > 0) {
            $order = Order::where('id', $request->id)->update([
                'updated' => time(),
                'status_for_cus' => 3,
            ]);
            OrderLog::add($request->id,'confirm_delivered');
            return \Lib::ajaxRespond(true, 'success');
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }

    /*Gan user xu ly don hang*/
    protected function ajaxAssignOrder4User(Request $request)
    {
        if (\Auth::user() && $request->id > 0) {
            $order = Order::where('id', $request->id)->update([
                'updated' => time(),
                'user_id' => ($request->is_take == 1 || $request->is_take == 3) ? \Auth::user()->id : 0,
                'status_for_cus' => 1,
            ]);
            if ($order == 1 && $request->is_take == 1) {
//                event('received', [$request->id, \Lib::getDefaultLang()]);
            }
            OrderLog::add($request->id,$request->is_take == 1 ? 'assign' : 'unassign');
            return \Lib::ajaxRespond(true, 'success');
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }
    /*Xac nhan cho thanh toan*/
    protected function ajaxConfirmOrderPendingPaid(Request $request)
    {
        if (\Auth::user() && $request->id > 0) {
            $order = Order::where('id', $request->id)->where('user_id', \Auth::user()->id)->first();
            if(!empty($order)) {
                $order->update(['updated' => time(), 'status' => 3]);
//                $order->stock()->update(['status' => 3]);
//                $order->item()->update(['status' => 3]);
                OrderLog::add($request->id,'payment_pending');
                return \Lib::ajaxRespond(true, 'success');
            }
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }
    /*Xac nhan thanh toan don*/
    protected function ajaxConfirmOrderPaid(Request $request)
    {
        if (\Auth::user() && $request->id > 0) {
            $order = Order::where('id', $request->id)->where('user_id', \Auth::user()->id)->first();
            if(!empty($order)) {
                DB::beginTransaction();
                try{
                    $order->update(['updated' => time(), 'status' => 4]);
                    event('order.payment',[$request->id]);
                    DB::commit();
                }catch(\Exception $e) {
                    DB::rollBack();
                    $msg = "Đã có lỗi xảy ra!";
                }

                /*Log*/
                OrderLog::add($order->id,'payment');
                \MyLog::do()->add($this->key . '-payment', $order->id);
                return \Lib::ajaxRespond(true, isset($msg) ? $msg : 'success');
            }
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }
    /*Xac nhan don hang*/
    protected function ajaxConfirmOrder(Request $request)
    {
        if (\Auth::user() && $request->id > 0) {
            $updated = Order::where('id', $request->id)->where('user_id', \Auth::user()->id)->update(['updated' => time(),'status' => 2]);

            if($updated) {
                $order = Order::find($request->id);
                if($order !== null) {
                    event('ticket.order_success', [$request->id]);
                    OrderLog::add($request->id, 'complete');
                    return \Lib::ajaxRespond(true, 'success');
                }
            }
        }
        return \Lib::ajaxRespond(false, 'Đơn hàng chưa được gán xử lý hoặc lỗi');
    }
    /*Xac nhan huy don hang*/
    protected function ajaxConfirmOrderCancel(Request $request)
    {
        if (\Auth::user() && $request->id > 0) {
            $order = Order::where('id', $request->id)->where('user_id', \Auth::user()->id)->first();
            if(!empty($order)){
                $order->update(['updated' => time(),'status' => -1]);

                \MyLog::do()->add($this->key . '-remove', $order->id);
                OrderLog::add($order->id,'delete',$request->reason);
//                event('admincancel', [$request->id, $request->reason, \Lib::getDefaultLang()]);
                return \Lib::ajaxRespond(true, 'success');
            }
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }
    /*Yeu cau hoan don hang*/
    protected function ajaxRequireRefundOrder(Request $request)
    {
        if (\Auth::user() && $request->id > 0) {
            $order = Order::find($request->id);
            if(!empty($order)){
                event('order.refund', [$request->id,$order,$request,5]);
                return \Lib::ajaxRespond(true, 'success');
            }
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }
    /*Xac nhan hoan don hang*/
    protected function ajaxConfirmRefundOrder(Request $request)
    {
        if (\Auth::user() && $request->id > 0) {
            $order = Order::where('id', $request->id)->first();
            if(!empty($order)){
                event('order.refund', [$request->id,$order,$request,6]);
                return \Lib::ajaxRespond(true, 'success');
            }
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }
    /*Hoàn thành hoàn tiền don hang*/
    protected function ajaxDoneRefundOrder(Request $request)
    {
        if (\Auth::user() && $request->id > 0) {
            $order = Order::where('id', $request->id)->first();
            if(!empty($order)){
                event('order.refund', [$request->id,$order,$request,7]);
                return \Lib::ajaxRespond(true, 'success');
            }
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }
    /*Huy yeu cau hoan don hang*/
    protected function ajaxCancelRefundOrder(Request $request)
    {
        if (\Auth::user() && $request->id > 0) {
            $order = Order::where('id', $request->id)->where('user_id', \Auth::user()->id)->first();
            if (!empty($order)) {
                event('order.delete', [$request->id,$order, $request->reason]);
                return \Lib::ajaxRespond(true, 'success');
            }
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }

    protected function ajaxWepayOrder(Request $request){
        if (\Auth::user() && $request->code !='') {
            $order = Order::getOrderByCode($request->code);
            if (!empty($order)) {
                $wepay = new WePayAPI();
                $wepay_order = $wepay->queryOrderStatus($order->code);
                return \Lib::ajaxRespond(true, 'success',$wepay_order);
            }
            return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
        }
    }

    protected function ajaxNganLuonOrder(Request $request){
        if (\Auth::user() && $request->code !='') {
            $order = Order::getOrderByCode($request->code);
            if (!empty($order)) {
                $nlcheckout= new NL_CheckOutV3(config('nganluong.MERCHANT_ID'),config('nganluong.MERCHANT_PASS'),config('nganluong.RECEIVER'),config('nganluong.URL_API'));
                $nl_result = $nlcheckout->GetTransactionDetail($order->nganluong_token);
                $nl_result->err_mess = $nlcheckout->GetErrorMessage( (string)$nl_result->error_code);
                $nl_result->payment_time = $order->payment_time > 0 ? \Lib::dateFormat($order->payment_time,'d/m/Y H:i') : '';
                return \Lib::ajaxRespond(true, 'success',$nl_result);
            }
            return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
        }
    }

    protected function ajaxBizPayOrder(Request $request)
    {
        if (\Auth::user() && $request->code !='') {
            $order = Order::getOrderByCode($request->code);
            if (!empty($order)) {
                $bizPay= new BizPay();
                $result = $bizPay->getInfoOrder($order->code);
                if(is_array($result) && count($result) > 0) {
                    $result_2 = $result[0];
                    $result_2->payment_time = $order->payment_time > 0 ? \Lib::dateFormat($order->payment_time,'d/m/Y H:i') : '';
                    return \Lib::ajaxRespond(true, 'success',$result_2);
                }
            }
            return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
        }
    }

}