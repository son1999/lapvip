<?php

namespace App\Modules\BackEnd\Controllers;

use App\Models\UserRole;
use Illuminate\Http\Request;

use App\Models\Role as THIS;

class RoleController extends BackendController
{
    protected $timeStamp = 'created';
    protected $delete = true;

    //config controller, ez for copying and paste
    public function __construct(){
        $this->bladeAdd = 'add';
        parent::__construct(new THIS(),[
            [
                'title' => 'required|max:250',
                'rank' => 'required|numeric',
            ]
        ]);

        if($this->form != 'list') {
            \View::share('roles', THIS::getPermissions());
        }
    }

    public function index(Request $request){
        $order = '`rank` ASC, created DESC';
        $cond = [];
        if($request->fullname != ''){
            $cond[] = ['title','LIKE','%'.$request->fullname.'%'];
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
        $this->addValidate(['title' => 'unique:roles,title'.($this->editMode?','.$this->editID:'')]);
    }

    public function beforeSave(Request $request, $ignore_ext = [])
    {
        if($request->rank == 0 || !\Auth::user()->checkMyRank($request->rank)){
            return $this->setError('Xếp hạng không hợp lệ');
        }

        if(empty($this->model->created)){
            $this->model->created = time();
        }

        $this->model->title = $request->title;
        $this->model->rank = $request->rank;
    }

    public function afterSave(Request $request){
        $tmp = [];
        $roles = THIS::getPermissions();
        foreach ($roles as $role => $v) {
            if ($request->has($role)) {
                $tmp[$role] = $request->get($role);
            }
        }
        $this->model->permit = json_encode($tmp);

        $this->model->save();
    }

    public function delete($id){
        $item = THIS::find($id);
        if($item) {
            if ($item->id == 1 || !\Auth::user()->checkMyRank($item->rank)) {
                return redirect()->route('admin.' . $this->key)->withErrors(['root_deny' => 'Không được phép thao tác với nhóm quyền cao hơn']);
            }
            $item->delete();
            UserRole::where('rid', '=', $id)->delete();
            return redirect()->route('admin.' . $this->key)->with('status', $this->title . ' <b>' . $item->title . '</b> đã bị xóa');
        }
        return redirect()->route('admin.' . $this->key)->withErrors(['not_existed' => $this->title . ' đã bị xóa hoặc không tồn tại']);
    }
}
