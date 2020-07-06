<?php

namespace App\Libs;

use App\Models\Coupon;
use App\Models\Product;

class CouponLib
{

    private static function checkObjCoupon(Coupon $coupon,$object_id){
        if($coupon->type != Coupon::$TYPE_ORDER) {
            if($coupon->object_id == '') {
                return false;
            }else {
                if(empty($object_id) || !isset($object_id[$coupon->type]) || empty($object_id[$coupon->type])){
                    return false;
                }
                $compare = explode(',',$coupon->object_id);
                $prd_discount = [];
                if($coupon->type == Coupon::$TYPE_PRODUCT) {

                    foreach($object_id[$coupon->type] as $item) {
                        if(in_array($item,$compare)) {
                            $prd_discount[] = $item;
                        }
                    }
                    return !empty($prd_discount) ? $prd_discount : false;
                }else if($coupon->type == Coupon::$TYPE_CATEGORY) {
                    foreach ($object_id[$coupon->type] as $key => $value) {
                        // Toan to object_id phai? nam trong mang object_id duoc giam gia
                        if (count(array_intersect($value,$compare)) > 0) {
                            $prd_discount[] = $key;
                        }
                    }
                }

                if(!empty($prd_discount)) {
                    return $prd_discount;
                }
            }
        }
        return true;
    }
    // Đây là hàm chcek coupon do hệ thông sinh ra
    // trong menu MGG hệ thống
    public static function checkCouponSys($coupon = '',$object_id = []) {
        $coupon = Coupon::couponCanUse($coupon);
        if(!empty($coupon)){
            $data_return = [];

            // check coupon ap dung cho danh muc hoac san pham
            $passing = self::checkObjCoupon($coupon,$object_id);

            $end_date = $coupon->expired;

            // Check xem coupon còn hạn hay ko, đã sử dụng hay chưa
            if( $end_date > time() && $passing) {
                $data_return = $data_return + [
                        'type' => $coupon->value <= 100 ? 'percent' : 'bonus',// Mặc định coupon hệ thống là dạng Phần trăm
                        'discount' => $coupon->value,
                        'coupon_code' => $coupon->code,
                        'passing' => is_array($passing) ? $passing : [],
                    ];
                return $data_return;
            }
        }
        return false;
    }

    /// $for_admin : Bỏ qua check hạn sử dụng,  bỏ qua check đã sử dụng
    /// Trong nghiep vụ admin sẽ tự xử lý
    public static function checkingCouponByCus($coupon = '',$object_id = [],$customer_id = 0,$group_customer = 0,$for_admin = false) {
        // check coupon hệ thống trước
        $coupon_sys = self::checkCouponSys($coupon,$object_id);
        if($coupon_sys != false) {
            return $coupon_sys;
        }
        return false;
    }

    public static function tickVoucherUsed($coupon = '',$customer_id = 0){
        $coupon = Coupon::couponCanUse($coupon);
        if(!empty($coupon)){
            $coupon->update(['quantity' => \DB::raw('quantity - 1'), 'used_times' => \DB::raw('used_times + 1')]);
            return $coupon;
        }
        return false;
    }

    public static function calcCoupon($coupon,$total,$cart_content) {
        $checking['category'] = Product::getAllCateFromPrdIds($cart_content['itm_ids']);
        $checking['product'] = $cart_content['itm_ids'];
        $grand_total = 0;
        $dccoupon = 0;
        $coupon = self::checkingCouponByCus($coupon,$checking);

        if(!empty($coupon) && $total > 0 && empty($coupon['passing'])) {
            $dccoupon = ($coupon['type']=='percent' ? ceil((($total*$coupon['discount']/100)/1000)*1000) : $coupon['discount']);
            $grand_total = $dccoupon <= $total ? $total - $dccoupon : 0;
        }elseif(!empty($coupon) && $total > 0 && !empty($coupon['passing'])) {
            $dccoupon = 0;
            $total2 = 0;
            $flip_arr = Lib::replace_key_by_val($cart_content['details'],'id');
            foreach($coupon['passing'] as $item) {
                $total2 += $flip_arr[$item]['price']*$flip_arr[$item]['quan'];//($coupon['type']=='percent' ? ceil((($flip_arr[$item]['price']*$coupon['discount']/100)/1000)*1000) : $coupon['discount']);
            }

            $dccoupon = ($coupon['type']=='percent' ? ceil((($total2*$coupon['discount']/100)/1000)*1000) : $coupon['discount']);
            $grand_total = $dccoupon <= $total ? $total - $dccoupon : 0;
        }else {
            $grand_total = $total;
        }

        return ['gr_total' => $grand_total,'dccoupon' => $dccoupon];
    }
}