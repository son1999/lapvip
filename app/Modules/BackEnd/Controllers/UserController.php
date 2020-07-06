<?php
namespace App\Modules\BackEnd\Controllers;

use App\Models\SysLog;
use Illuminate\Http\Request;

use App\Models\User as THIS;
use App\Models\UserRole;

class UserController extends BackendController
{
    protected $timeStamp = 'created';
    protected $titleField = 'user_name';

    public function __construct(){
        $this->bladeAdd = 'add';
        parent::__construct(new THIS(),[
            [
                'email' => 'required|email',
                'fullname' => 'required',
                'phone' => 'required|numeric',
                'password' => 'required|min:6',
                'password_confirm' => 'same:password',
                'roles' => 'required',
                'image' => 'required',
            ]
        ]);
        \View::share('roles', \Role::all()->sortBy('rank'));

        $this->registerAjax('change-password', 'ajaxChangePass');
        $this->registerAjax('user-active', 'ajaxUserActive', 'edit');
        $this->registerAjax('actived', 'ajaxActived');
    }

    public function index(Request $request){
        $order = 'active DESC, last_active DESC, last_login DESC, id DESC';
        $cond = [['status','>','0']];
        $data = false;
        if($request->status != ''){
            switch($request->status){
                case -1:case 0:
                    $cond[0] = ['status','=',$request->status];
                    break;
                case 1:case 2:
                    $cond[] = ['active', $request->status == 1 ? '<=' : '>', 0];
                    break;
                case 3:
                    $cond[] = ['last_login','=',0];
                    break;
                case 4:case 5:
                    $timeOnline = time() - THIS::CheckOnlineTime;
                    if($request->status == 4){
                        $data = THIS::where('status', '>', 0)
                            ->where('active', '>', 0)
                            ->where('last_active', '>', 0)
                            ->where(function($q) use ($timeOnline){
                                $q->where('last_active', '<', $timeOnline)
                                    ->orWhere('last_logout', '>', 0);
                            });
                        $cond = [];
                    }else {
                        $cond[] = ['active', '>', 0];
                        $cond[] = ['last_active', '>=', $timeOnline];
                    }
                    $cond[] = ['last_active', $request->status == 5 ? '>=' : '<', $timeOnline];
                    break;
            }
        }
        if($request->user_name != ''){
            $cond[] = ['user_name','LIKE','%'.$request->user_name.'%'];
        }
        if($request->email != ''){
            $cond[] = ['email','LIKE','%'.$request->email.'%'];
        }
        if($request->phone != ''){
            $cond[] = ['phone','=',$request->phone];
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
            $data = $data === false ? THIS::where($cond) : $data->where($cond);

        }
        if($request->role != ''){
            $allIds = UserRole::where('rid', $request->role)->get()->keyBy('uid')->toArray();
            if(!empty($allIds)){
                $data = $data->whereIn('id', array_keys($allIds));
            }
        }
        $data = $data->orderByRaw($order)->paginate($this->recperpage);
        return $this->returnView('index', [
            'data' => $data,
            'search_data' => $request,
            'statusOpt' => THIS::getStatusOpt()
        ]);
    }

    public function log(Request $request, $uid){
        $user = THIS::find($uid);
        if($user) {
            $data = SysLog::where('env', 'admin')
                ->where('uid', $user->id);
            if(!empty($request->time_from)){
                $timeStamp = \Lib::getTimestampFromVNDate($request->time_from);
                $data = $data->where('created', '>=', $timeStamp);
            }
            if(!empty($request->time_to)){
                $timeStamp = \Lib::getTimestampFromVNDate($request->time_to, true);
                $data = $data->where('created', '<=', $timeStamp);
            }
            $data = $data->orderByRaw('created DESC')
                ->paginate($this->recperpage);
            return $this->returnView('log', [
                'data' => $data,
                'search_data' => $request
            ]);

        }
        return $this->notfound();
    }

    public function showEditForm($uid){
        if($uid == \Auth::id()){
            if(session('status')){
                return redirect()->route('admin.'.$this->key.'.profile')->with('status', session('status'));
            }
            return redirect()->route('admin.'.$this->key.'.profile');
        }
        $data = THIS::find($uid);
        if($data) {
            $title = 'Sửa thông tin';
            \Lib::addBreadcrumb($title);
            return $this->returnView('edit', [
                'site_title' => $title,
                'data' => $data,
                'user_roles' => UserRole::where('uid', $uid)->get()
            ]);
        }
        return $this->notfound();
    }

    public function showProfileForm(){
        $title = 'Thông tin cá nhân';
        \Lib::addBreadcrumb($title);
        return $this->returnView('profile', [
            'site_title' => $title,
            'data' => \Auth::user()
        ]);
    }

    public function buildValidate(Request $request){
        $this->addValidate([
            'email' => 'unique:users,email,' . $this->editID,
            'phone' => 'unique:users,phone,' . $this->editID,
        ]);
        if($this->editMode){
            if(empty($request->password)) {
                $this->ignoreValidate('password');
            }
            if($request->editProfile > 0){
                $this->ignoreValidate('roles');
            }
        }else{
            $this->addValidate(['user_name' => 'required|min:3|max:20|regex:#^[_a-zA-Z][0-9_a-zA-Z]*$#|unique:users,user_name']);
        }
    }

    public function beforeSave(Request $request, $ignore_ext = []){
        if(!\Lib::is_mobile($request->phone)){
            $this->setError(['phone' => 'Số điện thoại không hợp lệ']);
            return false;
        }
        if(!$this->editMode){
            $this->model->created = time();
            $this->model->user_name = strtolower($request->user_name);
            $this->model->reg_ip = $request->ip();
            $this->model->active = 3;
            $this->model->status = 1;
        }
        if($request->password != '') {
            $this->model->password = bcrypt($request->password);
        }
        $this->model->fullname = $request->fullname;
        $this->model->email = $request->email;
        $this->model->phone = $request->phone;
        $this->uploadImage($request, $request->title.'-icon', 'image');
    }

    public function afterSave(Request $request){
        //roles
        if(!empty($request->roles)){
            UserRole::where('uid', '=', $this->model->id)->delete();
            foreach($request->roles as $rid){
                $role = new UserRole;
                $role->uid = $this->model->id;
                $role->rid = $rid;
                $role->save();
            }
        }
    }

    public function beforeDelete($user)
    {
        if($user->id == \Auth::id()){
            $this->setError(['remove_yourself' => 'Bạn không thể tự xóa']);
        }elseif($user->isRoot() || !\Auth::user()->biggerThanYou($user->id)){
            $this->setError(['remove_root' => 'Bạn không có quyền thực hiện thao tác này']);
        }
    }

    protected function ajaxChangePass(Request $request){
        if (!(\Hash::check($request->old, \Auth::user()->password))) {
            return \Lib::ajaxRespond(false, 'Mật khẩu hiện tại không đúng', 1);
        }
        if(strcmp($request->old, $request->new) == 0){
            return \Lib::ajaxRespond(false, 'Mật khẩu mới phải khác mật khẩu cũ', 2);
        }
        //Change Password
        $user = \Auth::user();
        $user->password = bcrypt($request->new);
        $user->save();
        \MyLog::do()->add('info-pwd');
        return \Lib::ajaxRespond(true, 'Đổi mật khẩu thành công!!! Yêu cầu đăng nhập lại', ['url' => route('logout')]);
    }

    protected function ajaxUserActive(Request $request){
        if($request->id > 0) {
            $user = THIS::find($request->id);
            if ($user) {
                if(\Auth::user()->biggerThanYou($user->id)) {
                    \MyLog::do()->add('user-active', $user->id, $request->status, $user->active);

                    $user->active = $request->status;
                    $user->save();
                    if ($user->active == 0) {
                        THIS::forceLogout($user->id);
                    }
                    return \Lib::ajaxRespond(true, 'success');
                }
                return \Lib::ajaxRespond(false, 'Không có quyền thao tác');
            }
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }

    protected function ajaxActived(Request $request){
        $user = \Auth::user();
        $user->last_active = time();
        $user->save();
        return \Lib::ajaxRespond(true, 'Actived');
    }
}
