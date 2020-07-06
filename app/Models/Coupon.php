<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    //
    public static $TYPE_ORDER = 'order';
    public static $TYPE_CATEGORY = 'category';
    public static $TYPE_PRODUCT = 'product';

    protected $table = 'coupons';
    public $timestamps = false;

    public $fillable = ['code','type','value','started','expired','user_id','created','id','used_times','quantity'];

    public static function listType() {
        $TYPE = [
            self::$TYPE_ORDER => 'Đơn hàng',
            self::$TYPE_CATEGORY => 'Danh mục',
            self::$TYPE_PRODUCT => 'Sản phẩm'
        ];
        return $TYPE;
    }

    public function lang(){
        $lang = config('app.locales');
        return isset($lang[$this->lang]) ? $lang[$this->lang] : 'vi';
    }

    public function type() {
        if(in_array($this->type,array_keys(self::listType()))) {
            return self::listType()[$this->type];
        }
        return __('site.khongxacdinh');
    }

    public function user(){
        return $this->belongsTo('App/Model/User','user_id');
    }

    public static function couponCanUse($coupon,$type = 'hotel') {
        return self::where('code',$coupon)
            ->where('status',1)
            ->where('expired', '>', time())->first();
    }

    public static function getCoupons(){
        $sql[] = ['status', '>', 0];

        $data = self::where($sql)
            ->orderByRaw('id DESC')
            ->get()
            ->keyBy('id');
        return $data;
    }

}
