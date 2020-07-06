<?php


namespace App\Modules\BackEnd\Controllers;

use App\Models\Comment as THIS;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Comment_NewsController extends BackendController
{
    protected $timeStamp = 'created';

    public function __construct(){
        parent::__construct(new THIS());

        $this->registerAjax('status_comment', 'ajaxShowhighComment');
        $this->registerAjax('reply-cmt-news', 'ajaxRepComment');
        $this->registerAjax('edit-cmt-news', 'ajaxeditComment');
    }
    public function delete($id){
        $item = $this->model::find($id);
        if($item){
            $user = Customer::where('id', $item->uid)->select('id','fullname')->first();
            $this->beforeDelete($item);
            if(!empty($this->error)){
                return redirect()->back()->withErrors($this->error);
            }
            if($this->delete || $this->softDelete) {
                $item->delete();
            }else {
                $item->status = -1;
                $item->aid = Auth::user()->id;
                $item->action = 'Delete';
                $item->save();
            }
            \MyLog::do()->add($this->key.'-remove', $item->id);
            $this->afterDelete($item);
            return redirect()->back()->with('status', 'Bình luận của: <b>'.$user['fullname'].'</b> đã bị xóa');
        }
        return $this->notfound($id);
    }

    protected function ajaxShowhighComment(Request $request){
        if($request->id > 0) {
            $data = $this->model::find($request->id)->first();
            if ($data) {
                $before = $data->status;
                $data->status = $before == 1 ? 2 : 1;
                $data->aid = Auth::user()->id;
                $data->action = 'Hidden';
                $data->save();
                \MyLog::do()->add($this->key.'-status_comment', $data->id, $data->status, $before);
                return \Lib::ajaxRespond(true, 'success');
            }
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }

    public function ajaxRepComment(Request $request){
        if (!empty($request->parent_id)) {
            $cmt = new THIS();
            $cmt->aid = \Auth::user()->id;
            $cmt->type = 2;
            $cmt->type_id = $request->type_id;
            $cmt->comment = $request->data;
            $cmt->status = 1;
            $cmt->created = time();
            $cmt->comment_parent = $request->parent_id;
            $cmt->save();
            \MyLog::do()->add($this->key.'-add', $cmt->id, $cmt);
            return \Lib::ajaxRespond(true, 'success', $cmt->id);
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }
    public function ajaxeditComment(Request $request){
        if (!empty($request->id)) {
            $data = $this->model::where('id', $request->id)->first();
            if($data){
                $data->comment = $request->dat;
                $data->save();
                \MyLog::do()->add($this->key.'-edit-cmt-news', $data->id, $data);
                return \Lib::ajaxRespond(true, 'success', $data->id);
            }

        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }
}