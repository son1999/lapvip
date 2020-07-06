<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstallmentSuccess extends Model
{
    //
    protected $table = 'installment_success';
    public $timestamps = false;

    public static function pushInstallmentSuccess($data){
        $ins = new InstallmentSuccess();

        $ins->_token = $data['_token'];
        $ins->buyer_sex = $data['buyer_sex'];
        $ins->name = $data['name'];
        $ins->date_of_birth = $data['date_of_birth'];
        $ins->cmtnd = $data['cmtnd'];
        $ins->phone = $data['phone'];
        $ins->product_id = $data['ProID'];
        $ins->filter_key = $data['filter_key'];
        $ins->quan = $data['quan'];
        $ins->type = $data['type'];
        $ins->installment_scenarios_id = $data['installment_scenarios_id'];
        $ins->month = $data['month'];
        $ins->prepaid_amount = $data['prepaid_amount'];
        $ins->difference = $data['difference'];
        $ins->monthly_installments = $data['monthly_installments'];
        $ins->total_cost = $data['total_cost'];
        $ins->point_shop = @$data['point_shop'];
        $ins->status = 0;
        $ins->created = strtotime(now());

        $ins->save();
    }

    public static function getOrderById($id) {
        $wery =  InstallmentSuccess::with(['product' => function($query){
            $query->select('id', 'title', 'image', 'alias', 'price');
        }, 'filters' =>function($query){
            $query->select('id', 'title', 'filter_cate_id');
        }, 'filters.filter_cate' => function($query){
            $query->select('id', 'title');
        }, 'warehouse']);
        $wery->where('id', $id);
        return $wery->first();
    }
    public function status(){
        switch($this->status){
            case 0: return $this->status == 0 ? 'Đơn mới' : '';break;
            case 1: return $this->status == 1 ? 'Đang xử lý' : '';break;
            case 2: return $this->status == 2 ? 'Đang trong tiến trình trả góp' : '';break;
            case 3: return $this->status == 3 ? 'Hoàn thành' : '';break;
            case 5: return $this->status == -1 ? 'Đơn hủy' : '';break;
            default: return '';break;
        }
        return __('site.khongxacdinh');
    }

    public function product(){
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function filters(){
        return $this->hasMany(Filter::class, 'id', 'filter_key');
    }

    public function installment_scenarios(){
        return $this->hasOne(InstallmentScenarios::class, 'id', 'installment_scenarios_id');
    }

    public function warehouse(){
        return $this->hasOne(Warehouse::class, 'id', 'point_shop');
    }
}