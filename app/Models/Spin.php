<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spin extends Model
{
    //
    public static $TYPE_ORDER = 'order';
    public static $TYPE_CATEGORY = 'category';
    public static $TYPE_PRODUCT = 'product';

    protected $table = 'spin';
    public $timestamps = false;

    public function coupon(){
        return $this->hasOne(Coupon::class, 'id', 'coup_id');
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

    public static function leaf(){
        return self::where('status',2)->limit(12)->get();
    }

    public static function couponCanUse($coupon,$type = 'hotel') {
        return self::where('code',$coupon)
            ->where('status',1)
            ->where('expired', '>', time())->first();
    }
}
