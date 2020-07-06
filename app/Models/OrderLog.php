<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderLog extends Model
{
    //
    protected $table = 'order_logs';
    public $timestamps = false;

    public static $actions = array(
        'add' => 'Thêm mới',
        'edit' => 'Sửa thông tin hóa đơn',
        'delete' => 'Hủy hóa đơn',
        'payment' => 'Xác nhận thanh toán',
        'payment_pending' => 'Xác nhận chờ thanh toán',
        'request_refund' => 'Yêu cầu hoàn tiền',
        'confirm_refund' => 'Xác nhận hoàn tiền',
        'confirm_transport' => 'Xác nhận đang giao',
        'confirm_delivered' => 'Xác nhận đã giao hàng',
        'done_refund' => 'Hoàn thành hoàn tiền',
        'send_mail' => 'Gửi mail thông báo',
        'assign' => 'Gán xử lý',
        'unassign' => 'Bỏ gán xử lý',
        'email_buy' => 'Gửi email mua hàng',
        'push_crm' => 'Đẩy thông tin sang CRM',
        'status_change' => 'Đổi trạng thái hóa đơn',
        'complete' => 'Hoàn thành đơn'
    );

    public function action(){
        $actions = self::$actions;
        return isset($actions[$this->action_key]) ? $actions[$this->action_key] : $this->action_key;
    }

    public static function add($id, $action = 'add',$note='')
    {
        $user = \Auth::user();
        //record new log
        $log = new OrderLog();
        $log->order_id = $id;
        $log->ip = \Request::ip();
        $log->action_key = $action;
        $log->username = (isset($user->user_name) ? $user->user_name : 'system');
        $log->created = time();
        $log->note = $note;
        $log->save();
        return $log->id;
    }
}