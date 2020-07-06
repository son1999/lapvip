<?php

namespace App\Models;

use App\Libs\FunctionLib;
use Illuminate\Database\Eloquent\Model;

class CustomerGroup extends Model
{
    protected $table = 'customer_group';
    public $timestamps = false;
    const GROUP_DEFAULT = 1; //end user
    const GROUP_SALE = 2;
    const LAYER_GROUP_CUSTOMER = [
        0 => 'End user',
    ];


    protected $casts = [
        'ranking' => 'array',
    ];

    public static function getDefGroup(){
        return CustomerGroup::with('details')->find(CustomerGroup::GROUP_DEFAULT);
    }

    public function isEndUser(){
        return $this->type == 0;
    }

    public function getImageUrl($size = 'original'){
        //return \ImageURL::getCustomerGroupImageUrl($this->image, $size);
        return \ImageURL::getImageUrl($this->image, 'customergroup', $size);
    }

    public static function options($type = 0){
        switch ($type){
            case 0: return 'Giảm giá % tổng hóa đơn';
            case 1: return 'Giảm giá (VNĐ) cho hóa đơn';
            case 2: return 'Giảm giá vé cho từng phòng/khách sạn';
        }
        return '---';
    }

    public function getDiscount($total = 0, $product_id = 0, &$percent=0) {
        $reward = 0;
        if ($this->percent > 0) {
            $percent = $this->percent;
            $reward = ceil($this->percent * $total) / 100;
        } elseif ($this->bonus > 0) {
            $reward = $this->bonus;
        }

        $reward_other = 0;
        if($this->other > 0){
            $details = $this->details;
            if($details){
                $check = $details->where('product_id', $product_id)->first();

                if($check){
                    if ($check->percent > 0) {
                        $percent = $check->percent;
                        $reward_other = ceil($check->percent * $total) / 100;
                    } elseif ($check->bonus > 0) {
                        $reward_other = $check->bonus;
                    }
                }
            }
        }
        $rt = $reward > $reward_other ? $reward : $reward_other;
        return round($rt/1000,0,PHP_ROUND_HALF_DOWN) * 1000;
    }

    public function getPerDiscount(){
        return $this->percent;
    }

    public function getLayerName(){
        return !empty(self::LAYER_GROUP_CUSTOMER[$this->type]) ? self::LAYER_GROUP_CUSTOMER[$this->type] : '---';
    }

    public function type(){
        $options = -1;
        if($this->percent > 0){
            $options = 0;
        }elseif($this->bonus > 0){
            $options = 1;
        }elseif($this->other > 0) {
            $options = 2;
        }
        return self::options($options);
    }

    public function val(){
        if($this->percent > 0){
            return $this->percent.'%';
        }
        if($this->bonus > 0){
            return \Lib::priceFormatEdit($this->bonus)['price'].'<sup class="text-danger">đ</sup>';
        }
        return '';
    }

    public static function getAlls($detail = false){
        if($detail) {
            $groups = CustomerGroup::with('details')->where('status', '>', '0');
        }else{
            $groups = CustomerGroup::where('status', '>', '0');
        }
        return $groups->orderByRaw('sort DESC, id desc')->get();
    }

    public function details()
    {
        return $this->hasMany('App\Models\CustomerGroupDetail', 'customer_group_id', 'id');
    }

    public function customers() {
        return $this->belongsToMany(Customer::class, 'customer_group_connect', 'customer_group_id', 'customer_id');
    }
}
