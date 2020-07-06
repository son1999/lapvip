<?php

namespace App\Modules\BackEnd\Controllers;

use App\Models\Customer;
use App\Models\CustomerGroup;
use App\Models\CustomerGroupConnect;
use App\Models\Order;
use App\Models\Subscriber;
use App\Models\ProductsViewed;
use Illuminate\Http\Request;
use App\Models\Customer as THIS;
use Illuminate\Support\Facades\Input;

class CustomerController extends BackendController
{
    //
    public function __construct(){
        parent::__construct(new THIS(),[
            [
                'email' => 'required|email',
                'fullname' => 'required',
                'password' => 'required|min:6',
                'password_confirm' => 'required|same:password'
            ]
        ]);

        \View::share('group', CustomerGroup::getAlls());
    }

    public function index(Request $request){
        $data = THIS::with('groups')->where('status', '>', 0);
        $order = 'created DESC, id DESC';
        $cond = [['status','>','0']];
        if($request->phone != ''){
            $cond[] = ['phone','LIKE','%'.$request->phone.'%'];
        }
        if($request->email != ''){
            $cond[] = ['email','LIKE','%'.$request->email.'%'];
        }
        if(!empty($request->time_from)){
            $timeStamp = \Lib::getTimestampFromVNDate($request->time_from);
            array_push($cond, ['created', '>=', $timeStamp]);
        }
        if(!empty($request->time_to)){
            $timeStamp = \Lib::getTimestampFromVNDate($request->time_to, true);
            array_push($cond, ['created', '<=', $timeStamp]);
        }

        if ($request->group > 0) {
            $groups = CustomerGroupConnect::where('customer_group_id', $request->group)->get()->pluck('customer_id')->all();
            if(!empty($groups)) {
                $data = $data->whereIn('id', $groups);
            }
        }

        if(!empty($cond)) {
            $data = $data->where($cond)->orderByRaw($order)->paginate($this->recperpage);
        }else{
            $data = $data->orderByRaw($order)->paginate($this->recperpage);
        }

        return $this->returnView('index', [
            'data' => $data,
            'search_data' => $request
        ]);
    }

    public function showEditForm($uid){
        $data = THIS::with('groups')
            ->where('id', $uid)
            ->where('status','>=', 0)
            ->first();
        set_old($data);
        if($data) {
            $list = Order::getAllOrdersByIdEmail($data->id,$data->email,'order',5);
            $title = 'Sửa thông tin';
            \Lib::addBreadcrumb($title);
            return $this->returnView('edit', [
                'site_title' => $title,
                'list_orders' => $list->appends(Input::except('page')),
                'prd_history' => ProductsViewed::with('viewed')->where('cus_id', $uid)->orderByRaw('created DESC, id DESC')->paginate(10,['*'],'pag'),
                'data' => $data
            ]);
        }
        return $this->notfound();
    }

    public function buildValidate(Request $request){
        $this->addValidate(['email' => 'unique:customers,email,' . $this->editID]);
        if(!empty($request->phone)){
            $this->addValidate(['phone' => 'numeric|unique:customers,phone,' . $this->editID]);
        }
        if($this->editMode && empty($request->password)){
            $this->ignoreValidate(['password', 'password_confirm']);
        }
    }

    public function beforeSave(Request $request, $ignore_ext = []){
        if(!$this->editMode){
            $this->model->created = time();
            $this->model->user_name = strtolower($request->email);
            $this->model->reg_ip = $request->ip();
            $this->model->active = 1;
            $this->model->status = 1;
        }
        $this->model->fullname = $request->fullname;
        $this->model->email = strtolower($request->email);
        $this->model->phone = $request->phone;
        if($request->password != '') {
            $this->model->password = bcrypt($request->password);
        }
    }

    public function afterSave(Request $request){
        $subscriber = Subscriber::where('email', $this->model->email)->first();
        if (empty($subscriber)) {
            $subscriber = new Subscriber();
            $subscriber->email = $this->model->email;
            $subscriber->created = time();
        }
        $subscriber->customer_id = $this->editID;
        $subscriber->save();

        if(!empty($request->groups)){
            $this->model->addToGroup($request->groups);
        }else {
            $this->model->addToGroup(CustomerGroup::GROUP_DEFAULT);
        }
    }
}
