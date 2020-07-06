<?php
namespace App\Libs;

use App\Models\Role;
use App\Models\SysLog;

class MyLog{
    protected static $instance;

    protected static $actions = [
        'info-login' => 'Đăng nhập',
        'info-logout' => 'Đăng xuất',
        'info-pwd' => 'Thay đổi mật khẩu',
        'info-pwd-reset' => 'Lấy lại mật khẩu',
        'order-payment' => 'Cập nhật trạng thái thanh toán',
        'order-refund' => 'Hoàn tiền',
        'upload' => 'Upload ảnh',
        'user-active' => 'Kích hoạt người dùng',
        'tag-add' => 'Thêm tag',
        'tag-remove' => 'Xóa tag',
        'news-status' => 'Cập nhật trạng thái hiển thị tin tức',
        'page-status' => 'Cập nhật trạng thái hiển thị trang tĩnh',
    ];

    public function __construct(){
        $roles = Role::getPermissions();

        foreach ($roles as $key => $value){
            if(isset($value['perm']['add'])){
                self::$actions[$key.'-add'] = 'Quản trị '.$value['title'].' - Thêm mới';
            }
            if(isset($value['perm']['edit'])){
                self::$actions[$key.'-edit'] = 'Quản trị '.$value['title'].' - Sửa';
            }
            if(isset($value['perm']['delete'])){
                self::$actions[$key.'-delete'] = 'Quản trị '.$value['title'].' - Xóa';
            }
        }
    }

    public function doMyself($action = ''){
        return str_contains($action, 'info-');
    }

    public static function do() {
        if (empty(self::$instance)) {
            self::$instance = new MyLog();
        }
        return self::$instance;
    }

    public function getAction($action = ''){
        return isset(self::$actions[$action]) ? self::$actions[$action] : $action;
    }

    public function add($action = '', $id = 0, $after = '', $before = '', $note = ''){
        $log = new SysLog();
        $log->env = \Lib::appEnv();
        if($log->env == 'admin'){
            $log->uid = \Auth::id();
            if($log->uid > 0) {
                $log->username = \Auth::user()->user_name;
            }
        }else{
            $log->uid = \Auth::guard('customer')->id();
            if($log->uid > 0){
                $log->username = \Auth::user()->email;
            }
        }
        if($log->uid > 0 || $this->doMyself($action)){
            $mobile = \Lib::mobile_device_detect();
            if (is_array($mobile)) {
                $mobile = !empty($mobile) && $mobile[0] == 1;
            }
            if(!empty($before) && !$this->isJsonStr($before)) {
                if (is_object($before)) {
                    $before = $before->toArray();
                }
                $after = json_encode($after, JSON_UNESCAPED_UNICODE);
            }
            if(!empty($after) && !$this->isJsonStr($after)) {
                if (is_object($after)) {
                    $after = $after->toArray();
                }
                $after = json_encode($after, JSON_UNESCAPED_UNICODE);
            }
            $log->ip = \Lib::get_client_ip();
            $log->action = $action;
            $log->object_id = $id;
            $log->note = $note;
            $log->after = $after;
            $log->before = $before;
            $log->url = url()->full();

            $log->device = $mobile ? 1 : 0;
            $log->created = time();
            try{
                $log->save();
                return true;
            }catch(\Exception $e){
                return false;
            }
        }
        return false;
    }

    public function isJsonStr($string){
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}