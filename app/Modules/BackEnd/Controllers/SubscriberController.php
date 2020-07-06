<?php

namespace App\Modules\BackEnd\Controllers;

use Illuminate\Http\Request;

use App\Models\Subscriber as THIS;


class SubscriberController extends BackendController
{
    protected $timeStamp = 'created';
    protected $titleField= 'email';

    //config controller, ez for copying and paste
    public function __construct(){
        $this->bladeAdd = 'add';
        parent::__construct(new THIS(), [['email' => 'required|email']]);
    }

    public function index(Request $request){
        $order = 'created DESC, id DESC';
        $cond = [['status','>','0']];
        if($request->email != ''){
            $cond[] = ['email','LIKE','%'.$request->email.'%'];
        }
        if($request->phone != ''){
            $cond[] = ['phone','LIKE','%'.$request->phone.'%'];
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
            'search_data' => $request,
        ]);
    }

    public function buildValidate(Request $request){
        $this->addValidate(['email' => 'unique:subscribers,email,'.$this->editID]);
    }
}
