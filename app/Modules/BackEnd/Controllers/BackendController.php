<?php

namespace App\Modules\Backend\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BackendController extends Controller
{
    public $model;
    public $valid = [];
    public $validIgnore = [];
    public $error = [] ;
    protected $timeStamp = ''; // neu co sẽ tu dong luu duoi dang time()
    protected $imageField = 'image'; //ten truong luu anh
    protected $bannerField = 'banner'; //ten truong luu banner
    protected $titleField = 'title'; //ten truong tieu de

    protected $title = '';
    protected $key = '';
    protected $folder_upload = '';
    protected $recperpage = 20;
    protected $editID = 0;
    protected $editMode = false;
    protected $delete = false; // true = xoa vinh vien | false = update status = -1
    protected $softDelete = false; // true = update deleted_at
    protected $form = ''; // list|edit|add
    protected $ajax = [];
    protected $addMore = false;
    protected $bladeAdd = 'edit';
    protected $process_laters;

    function __construct($model = false, $valid = false){
        switch (\Route::current()->getActionMethod()){
            case 'showAddForm': $this->form = 'add'; break;
            case 'showEditForm': $this->form = 'edit'; break;
            case 'index': $this->form = 'list'; break;
            default: $this->form = 'other';
        }
        $calledName = get_called_class();
        $tmp = explode('\\',$calledName);
        $permission = config('permission');
        if(is_array($tmp)){
            $router = str_replace('controller','',strtolower(end($tmp)));
            if($router){
                foreach ($permission['backend'] as $k=>$v){
                    if(strtolower($v['controller']) == $router){
                        $this->title = $v['title'];
                        $this->key = $k;
                        break;
                    }
                }
            }
        }
        //$this->middleware('auth');
        \View::share('key', $this->key);
        if(!empty($this->form) && $this->form != 'other') {
            $this->middleware(function ($request, $next) {
                \View::share('permission', \Role::getPermOfUserByKey($this->key));
                return $next($request);
            });
            \View::share('langOpt', config('app.locales'));
//            \View::share('key', $this->key);

            \Lib::addBreadcrumb();
            \Lib::addBreadcrumb($this->title, $this->form == 'list' ? '' : route('admin.' . $this->key));
        }
        if(!empty($valid)){
            $this->valid = $valid;
        }
        if(!empty($model)){
            $this->model = $model;
        }

        $this->process_laters = new \stdClass();

        $this->registerAjax('status', 'ajaxStatus', 'edit');
        $this->registerAjax('status-banner', 'ajaxStatusBanner', 'edit');
        $this->registerAjax('hot', 'ajaxHot', 'edit');
        $this->registerAjax('copy', 'ajaxCopy', 'edit');
        $this->registerAjax('status_comment', 'ajaxShowhighComment');
    }

    public function index(Request $request){
        //todo here
    }

    public function showAddForm(){
        return $this->returnView($this->bladeAdd);
    }

    public function showEditForm($id){
        $data = $this->model::find($id);
        set_old($data);
        if($data) {
            return $this->returnView('edit', ['data' => $data]);
        }
        return $this->notfound($id);
    }

    public function returnView($blade,$dataExt = [],$breadcrumb = ''){
        
        $def_title = $this->title;
        if($this->form == 'edit' || $this->form == 'add'){
            $def_title = $this->form == 'edit' ? 'Sửa thông tin' : 'Thêm mới';
            if(empty($breadcrumb)) {
                \Lib::addBreadcrumb($def_title);
            }
        }
        if(!empty($breadcrumb)){
            \Lib::addBreadcrumb($breadcrumb);
        }
        $default = ['site_title' => $def_title];
        return view('BackEnd::pages.'.$this->key.'.'.$blade, array_merge($default,$dataExt));
    }

    public function notfound($id = 0){
        return redirect()->route('admin.'.$this->key)->withErrors(['not_existed' => $this->title.($id > 0 ? ' có ID: '.$id : '').' đã bị xóa hoặc không tồn tại']);
    }

    public function delete($id){
        $item = $this->model::find($id);
        if($item){
            $titleField = $this->titleField;
            $this->beforeDelete($item);
            if(!empty($this->error)){
                return redirect()->route('admin.'.$this->key)->withErrors($this->error);
            }
            if($this->delete || $this->softDelete) {
                $item->delete();
            }else {
                $item->status = -1;
                $item->save();
            }
            \MyLog::do()->add($this->key.'-remove', $item->id);
            $this->afterDelete($item);
            return redirect()->route('admin.'.$this->key)->with('status', $this->title.' <b>'.$item->$titleField.'</b> đã bị xóa');
        }
        return $this->notfound($id);
    }

    public function save(Request $request, $id = 0){
        $this->editID = $id;
        $this->editMode = $id > 0;
        if(!empty($this->valid)){
            $this->processValidate($request);
        }
        
        if($this->editMode){
            $this->model = $this->model::find($id);
            if(empty($this->model)){
                return $this->notfound($id);
            }
        }
        $before = $this->model;

        //hook before save 
        $this->beforeSave($request);
        //return if error
        
        if(!empty($this->error)){
            return redirect()->back()->withInput()->withErrors($this->error);
        }

        // dd($this->model->save());
        if( $request->product_id ){
            $product_id = implode(',', $request->product_id);
        }
        try{
            $pid = 0;
            if($request->query() && $request->query('parent')){
                $pid = $request->query('parent');
                $this->model->pid = $pid;
            }
            $this->model->save();
            $this->editID = $this->model->id;
            //hook after save
            $this->afterSave($request);

            //return if error
            if(!empty($this->error)){
                return redirect()->back()->withInput()->withErrors($this->error);
            }

            $titleField = $this->titleField;
            if($this->editMode){
                \MyLog::do()->add($this->key.'-edit', $this->model->id, $this->model, $before);
                return redirect()->route('admin.'.$this->key.'.edit', $this->model->id)->with('status', $this->title.' <b>'.$this->model->$titleField.'</b> đã được cập nhật');
            }
            \MyLog::do()->add($this->key.'-add', $this->model->id, $this->model);
            if($this->addMore){
                return redirect()->back()->with('status', 'Đã thêm '.strtolower($this->title).' <b>'.$this->model->$titleField.'</b>');
            }
            return redirect()->route('admin.'.$this->key)->with('status', 'Đã thêm '.strtolower($this->title).' <b>'.$this->model->$titleField.'</b>');
        }catch(\Exception $e){
            throw $e;
            return redirect()->back()->withInput()->withErrors(['error:' => 'Có lỗi trong quá trình lưu dữ liệu! Vui lòng thử lại! '.$e->getMessage()]);
        }
    }

    public function beforeSave(Request $request, $ignore_ext = []){
        $titleField = $this->titleField;
        $data = $request->all();
        $ignore = array_merge(['_token', 'id'], $ignore_ext);
        foreach ($ignore as $i){
            unset($data[$i]);
        }
        foreach($data as $k => $v){
            switch ($k){
                case 'email':
                    $this->model->$k = strtolower($v);
                    break;
                case 'password':
                    if(!empty($v)) {
                        $this->model->$k = bcrypt($v);
                    }
                    break;
                case 'title_seo':
                    $this->model->$k = !empty($request->title_seo) ? $request->title_seo : $request->$titleField;
                    break;
                default:
                    $this->model->$k = $v;
            }
        }
        $timeStamp = $this->timeStamp;
        if(!empty($timeStamp) && empty($this->model->$timeStamp)){
            $this->model->$timeStamp = time();
        }
        $this->uploadImage($request, $request->$titleField, $this->imageField);
        
    }

    public function afterSave(Request $request){
        //todo here
    }

    public function beforeDelete($item){
        //todo here
    }

    public function afterDelete($item){
        //todo here
    }

    public function buildValidate(Request $request){
        //todo here
    }

    public function uploadImage(Request $request, $title, $imageField = '',$key = ''){
        if ($request->hasFile($imageField)) {
            $image = $request->file($imageField);
            if ($image->isValid()) {
                $fname = \ImageURL::makeFileName($title, $image->getClientOriginalExtension());
                $image = \ImageURL::upload($image, $fname,  (empty($key) ? ($this->folder_upload ? $this->folder_upload : $this->key) : $key));
                // dd($fname);
                if($image){
                    if(!empty($imageField)) {
                        $this->model->$imageField = $fname;
                    }
                    return $fname;
                }else{
                    $this->setError([$imageField => 'Upload ảnh lên server thất bại!']);
                }
            }else{
                $this->setError([$imageField => 'Upload ảnh thất bại!']);
            }
        }
        return '';
    }

    public function uploadBanner(Request $request, $title, $bannerField = ''){
        if ($request->hasFile($bannerField)) {
            $image = $request->file($bannerField);

            if ($image->isValid()) {
                $fname = \ImageURL::makeFileName($title, $image->getClientOriginalExtension());
                $image = \ImageURL::upload($image, $fname, $this->key);
                // dump($fname);
                // die(__FILE__.__LINE__);
                if($image){
                    if(!empty($bannerField)) {
                        $this->model->$bannerField = $fname;
                    }
                    return $fname;
                }else{
                    $this->setError([$bannerField => 'Upload ảnh banner lên server thất bại!']);
                }
            }else{
                $this->setError([$bannerField => 'Upload ảnh banner thất bại!']);
            }
        }
        return '';
    }

    public function addValidate($rules = [], $msg = []){
        if(!empty($msg)){
            foreach($msg as $k => $m){
                $this->valid[1][$k] = $m;
            }
        }
        if(!empty($rules)){
            foreach ($rules as $k => $m){
                if(!isset($this->valid[0][$k])){
                    $this->valid[0][$k] = $m;
                }else{
                    $this->valid[0][$k] .= '|'.$m;
                }
            }
        }
    }

    public function ignoreValidate($skip = ''){
        if(!empty($skip)) {
            if(is_array($skip)) {
                foreach ($skip as $k) {
                    if (isset($this->valid[0][$k])) {
                        unset($this->valid[0][$k]);
                    }
                }
            }else{
                if (isset($this->valid[0][$skip])) {
                    unset($this->valid[0][$skip]);
                }
            }
        }
    }
    public function processValidate($request){
        $this->buildValidate($request);
        $tmpRule = isset($this->valid[0]) ? $this->valid[0] : [];
        $msg = isset($this->valid[1]) ? $this->valid[1] : [];
        $rule = [];
        $customAttributes = [];
        if(!empty($tmpRule)){
            foreach ($tmpRule as $k=>$r){
                if(is_array($r)){
                    $rule[$k] = isset($r[0]) ? $r[0] : '';
                    $customAttributes[$k] = isset($r[1]) ? $r[1] : $k;
                }
                else{
                    $rule[$k] = $r;
                }
            }
        }
        $this->validate($request, $rule, $msg,$customAttributes);

    }
    public function setError ($error){
        $this->error[] = $error;
    }

    public function ajax(Request $request, $cmd){
        $data = \Lib::ajaxRespond(false, 'Nothing...');
        if(!empty($this->ajax) && !empty($this->ajax[$cmd])){
            $perm = !empty($this->ajax[$cmd]['perm']) ? \Lib::can(false,$this->ajax[$cmd]['perm'],$this->key) : true;
            $data = $perm ? call_user_func( array($this, $this->ajax[$cmd]['func']), $request) : \Lib::ajaxRespond(false, 'Access denied');
        }
        return response()->json($data);
    }
    protected function registerAjax($cmd, $func, $perm = ''){
        // dd($func,$cmd,$perm);
        $this->ajax[$cmd] = ['func' => $func, 'perm' => $perm];
    }
    protected function ajaxStatus(Request $request){
        if($request->id > 0) {
            $data = $this->model::find($request->id);
            if ($data) {
                $before = $data->status;
                $data->status = $request->show == 1 ? 2 : 1;
                $data->save();
                \MyLog::do()->add($this->key.'-status', $data->id, $data->status, $before);
                return \Lib::ajaxRespond(true, 'success');
            }
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }

    protected function ajaxStatusBanner(Request $request){
        if($request->id > 0) {
            $data = $this->model::find($request->id);
            if ($data) {
                $before = $data->is_banner;
                $data->is_banner = $request->show == 1 ? 2 : 1;
                $data->save();
                \MyLog::do()->add($this->key.'-is_banner', $data->id, $data->is_banner, $before);
                return \Lib::ajaxRespond(true, 'success');
            }
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }



    protected function ajaxHot(Request $request){
        if($request->id > 0) {
            $data = $this->model::find($request->id);
            if ($data) {
                $before = $data->hot;
                $data->hot = $request->show == 1 ? 1 : 0;
                $data->save();
                \MyLog::do()->add($this->key.'-hot', $data->id, $data->hot, $before);
                return \Lib::ajaxRespond(true, 'success');
            }
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }
    protected function ajaxCopy(Request $request){
        return \Lib::ajaxRespond(true, 'success');
    }

    protected function unsetFields($fields = [])
    {
        foreach($fields as $item) {
            unset($this->model->$item);
        }
    }
}
