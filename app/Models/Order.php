<?php

namespace App\Models;

use App\Libs\CouponLib;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class Order extends Model
{
    //
    protected $table = 'orders';
    public $timestamps = false;
    public $fillable = ['updated','status','user_id'];

    const TIME_PENDING_TICKET = 30*60*60;// 30 phút

    public static function listOrderFrom() {
        $ORDER_FROM = [
            'chat' => 'Chat',
            'web' => 'Web',
            'mobile' => 'Mobile'
        ];
        return $ORDER_FROM;
    }
    const KEY = 'order';

    const METHOD = [
            'ATM' => 'Chuyển khoản qua Ngân Hàng',
            'COD' => 'Thanh toán COD',
            'OFF' => 'Tại Shop',
        ];

    public static function listStatus() {
        $STATUS = [
            7 => __('site.dahoantien'),
            6 => __('site.xacnhanhoantien'),
            5 => __('site.yeucauhoantien'),
            4 => __('site.dathanhtoan'),
            3 => __('site.chothanhtoan'),
            -1 => __('site.dahuy'),
            0 => __('site.hethang'),
            1 => __('site.yeucaumoi/dangxuly'),
            2 => __('site.hoanthanh')
        ];
        return $STATUS;
    }

    public static $statusPass = [1,2,3,4,5];

    public static function statusForCus() {
        $STATUS = [
            3 => __('site.giaohangthanhcong'),
            2 => __('site.danggiaohang'),
            1 => __('site.dangdongoi'),
            0 => __('site.dathangthanhcong'),
        ];
        return $STATUS;
    }

    public static function paymentType(){
        $options = [
            0 => __('site.tienmat'),
            1 => __('site.thanhtoanonline'),
            2 => __('site.chuyenkhoanATM'),
        ];

        return $options;
    }

    public function paymentTypeName(){
        return isset(self::paymentType()[$this->payment_type]) ? self::paymentType()[$this->payment_type] : $this->payment_type;
    }

    public static function listType() {
        $TYPE = [
            'order' => __('site.donhang'),
//            'table'=> __('site.dondatban'),
        ];
        return $TYPE;
    }

    public static function shippingNotices(){
        $options = [
            1 => __('site.cangnhanhcangtot'),
            2 => __('site.binhthuong')
        ];
        return $options;
    }
    public function status(){
        switch($this->status){
            case 1: return $this->user_id == 0 ? __('site.yeucaumoi') : 'Đang xử lý';break;
            default: return self::listStatus()[$this->status];break;
        }
        return __('site.khongxacdinh');
    }

    public function isPaid()
    {
        return $this->payment_status > 0 || $this->status == 4 || $this->status == 2;
    }

    public function statusCus(){
        return self::statusForCus()[$this->status_for_cus];
    }

    public function orderFrom() {
        return self::listOrderFrom()[$this->order_from];
    }

    public function type() {
        if(in_array($this->type,array_keys(self::listType()))) {
            return self::listType()[$this->type];
        }
        return __('site.khongxacdinh');
    }

    public function items() {
        return $this->hasMany('App\Models\OrderItem', 'order_id', 'id');
    }


    public function user() {
        return $this->hasOne('App\Models\User','id','user_id');
    }

    public function province() {
        return $this->hasOne('App\Models\GeoProvince','id','province_id');
    }
    public function district() {
        return $this->hasOne('App\Models\GeoDistrict','id','district_id');
    }
    public function ward() {
        return $this->hasOne('App\Models\GeoWard','id','ward_id');
    }

    public function typeName(){
        return isset(self::listType()[$this->type]) ? self::listType()[$this->type] : $this->type;
    }

    public function order_refund() {
        return $this->hasOne('App\Models\OrderRefund', 'order_id', 'id');
    }

    public static function getByTokenTracking($token = '',$non_processing = false) {
        $wery = Order::where('token_tracking',$token);
        if($non_processing) {
            $wery->where('status',1);
            $wery->where('user_id',0);
        }
        $wery->with(['items','items.product','items.product.category','province','district','ward']);

        return $wery->first();
    }

    public static function createItemsOrder(Order $order,$data){

        $order->items()->delete();

        $order->total_price = $data['total'];
        $order->items()->saveMany(OrderItem::returnOrderItemObjs($data['details'], $order->id));
        $order->save();

        return $order;
    }

    public static function createOrder($data,$type = 'order', $err = false){
        $order = new Order();
        $order->type = $type;
        $order->created = time();

        $order->phone = $data->phone;
        $order->fullname = $data->fullname;
//        $order->note = $data->note;
//        $order->order_from = $request->order_from;
        $order->customer_id = $data->customer_id;

//        $check_not_enough = TicketStorage::checking_not_enough($data->booking_items);
//dd($check_not_enough);
        $order->email = $data->email;

        $order->address = $data->address;
//            $order->address_type = $data->address_type;
        $order->province_id = $data->province_id;
        $order->district_id = $data->district_id;
//        $order->ward_id = @$data->ward_id;

        if(!empty($data->coupon_code)) {
            $return_coupon = CouponLib::calcCoupon($data->coupon_code,$data->total_price);
        }
        $order->total_price = isset($return_coupon['grand_total']) && $return_coupon['grand_total'] > 0 ? $return_coupon['grand_total'] : $data->total_price;
        $order->coupon_code = isset($return_coupon['grand_total']) && $return_coupon['grand_total'] > 0 ? $data->coupon_code : '';
        $order->coupon_value = isset($return_coupon['grand_total']) && $return_coupon['grand_total'] > 0 ? $return_coupon['dccoupon'] : 0;
//        $order->total_price = $data->total_price;
        $order->fee_shipping = isset($data->shipping_fee) && !empty($data->shipping_fee) ? $data->shipping_fee : 0;
        $order->payment_type = isset($data->payment_type) && !empty($data->payment_type) ? $data->payment_type : 0;
//        $order->land_id = $data->land_id;
//        $order->bank_id = $data->bank_id;

//        $order->status = ($check_not_enough && $check_not_enough['has_item_notenough'] === false ? 1 : 0);
        $order->status = 1;

        try {
            $order->save();
            $order->code = self::makeCode($type, $order->id,\Lib::getDefaultLang());
            if($type == 'order') {
                $order->items()->saveMany(OrderItem::returnOrderItemObjs($data->booking_items, $order->id));
            }
            $order->token_tracking = self::genTokenTracking($order->code);
            $order->save();

//            if($order->status == 1) {
//                $now = time();
//                foreach($check_not_enough['items'] as $item) {
//                    TicketStorage::whereIn('id',$item['list_ticket_available'])->update(['status' => 2,'order_id' => $order->id,'expired' => ($now + Order::TIME_PENDING_TICKET)]);
//                }
//            }
        }catch(\Exception $e){
            throw $e;
//            $err = 'error';
        }
        return $order;
    }

    public static function makeCode($type = 'order', $id = 0,$lang = ''){
        return $lang.'_'.$type.$id.\Lib::dateFormat(time(), 'dmy');
    }

    public static function genTokenTracking($code = '') {
        return sha1(uniqid('', true).str_random(35).$code.microtime(true));
    }

    public static function getOrderByCode($code){
        return Order::with(['items','items.product','items.product.category','province', 'ward', 'district'])
            ->where('code', $code)
//            ->where('status', '>', 0)
            ->first();
    }

    public static function getOrderById($id,$customer_id = 0) {
        $wery =  Order::with(['items','province', 'ward', 'district']);
        $wery->where('id', $id);
        if($customer_id > 0) {
            $wery->where('customer_id',$customer_id);
        }
        return $wery->first();
    }

    public static function getAllOrdersByCus($cus_id = 0,$perpage = 5) {
        if($cus_id > 0) {
            $wery = Order::where('customer_id',$cus_id);
            $wery->orderBy('id','desc');
            $wery->with('items');

            return $wery->paginate($perpage);
        }
        return false;
    }

    public static function getAllOrdersByIdEmail($cus_id = 0,$email = '',$type = 'order',$perpage = 5) {
        if($email && $cus_id) {
            $wery = Order::where(function ($q) use ($cus_id,$email) {
                $q->where('customer_id', $cus_id);
                $q->orWhere('email', $email);
            });
            if(in_array($type,array_keys(self::listType()))) {
                $wery->where('type', $type);
            }
            $wery->orderBy('id','desc');
            $wery->with('items');

            return $wery->paginate($perpage);
        }
        return false;
    }

    public static function revenue($time_from,$time_to,$done = 0)
    {
        $wery = DB::table('orders');
        $wery->select(DB::raw('sum(total_price) as gross_revenue'));
        if($done) {
            $wery->where('status', 4);
        }
        if($time_from){
            $wery->where('created','>=',$time_from);
        }
        if($time_to) {
            $wery->where('created', '<=', $time_to);
        }

        return $wery->value('gross_revenue');
    }

    public static function numOfOrders($time_from,$time_to,$done = 0)
    {
        $wery = DB::table('orders');
        $wery->select(DB::raw('count(id) as total_orders'));
        if($done) {
            $wery->where('status', 4);
        }
        if($time_from){
            $wery->where('created','>=',$time_from);
        }
        if($time_to) {
            $wery->where('created', '<=', $time_to);
        }

        return $wery->value('total_orders');
    }

    public static function avgOrderValue($time_from,$time_to,$done = 0)
    {
        $wery = DB::table('orders');
        $wery->select(DB::raw('ROUND(AVG(total_price)) as avg_total'));
        if($done) {
            $wery->where('status', 4);
        }
        if($time_from){
            $wery->where('created','>=',$time_from);
        }
        if($time_to) {
            $wery->where('created', '<=', $time_to);
        }

        return $wery->value('avg_total');
    }

    public static function topCustomers($time_from,$time_to,$limit = 10)
    {
        $wery = \DB::table('orders');
        $wery->leftJoin('customers','customers.id','=','orders.customer_id');
        $wery->select(\DB::raw('sum(orders.total_price) as total_spend,count(orders.id) as total_orders,orders.customer_id, IF(customers.fullname != \'\', customers.fullname, \'Khách vãng lai\') as fullname'));
        if($time_from){
            $wery->where('orders.created','>=',$time_from);
        }
        if($time_to) {
            $wery->where('orders.created', '<=', $time_to);
        }
        $wery->where('orders.status', 4);
        $wery->groupBy('customer_id');
        $wery->orderBy('total_orders','desc');
        $wery->limit($limit);
        return $wery->get();
    }

    public static function topCoupon($time_from,$time_to,$limit = 10)
    {
        $wery = \DB::table('orders');
        $wery->join('coupons','coupons.code','=','orders.coupon_code');
        $wery->select(\DB::raw('coupons.used_times,coupons.code,sum(orders.coupon_value) as amount'));
        if($time_from){
            $wery->where('orders.created','>=',$time_from);
        }
        if($time_to) {
            $wery->where('orders.created', '<=', $time_to);
        }
        $wery->where('orders.status', 4);
        $wery->orderBy('used_times','desc');
        $wery->limit($limit);
        return $wery->get();
    }

    public static function topProducts($time_from,$time_to,$limit = 10)
    {
        $wery = \DB::table('orders');
        $wery->select(\DB::raw('products.title,order_items.product_id,sum(order_items.quantity) as items_sold,sum(order_items.quantity*order_items.price) as net_value'));
        $wery->join('order_items','order_items.order_id','=','orders.id');
        $wery->join('products','order_items.product_id','=','products.id');
        if($time_from){
            $wery->where('orders.created','>=',$time_from);
        }
        if($time_to) {
            $wery->where('orders.created', '<=', $time_to);
        }
        $wery->where('orders.status',4);
        $wery->groupBy('product_id');
        $wery->orderBy('items_sold','desc');
        $wery->limit($limit);
        return $wery->get();
    }

    public static function topCategories($time_from,$time_to,$limit = 10)
    {
        $wery = \DB::table('order_items');
        $wery->select(\DB::raw('categories.title as cate_title,categories.id as cate_id,order_items.product_id,sum(order_items.quantity) as total_items,sum(order_items.price*order_items.quantity) as amount'));
        $wery->join('orders','orders.id','=', 'order_items.order_id');
        $wery->join('products','products.id','=', 'order_items.product_id');
        $wery->join('categories','products.cat_id', '=', 'categories.id');
        if($time_from){
            $wery->where('orders.created','>=',$time_from);
        }
        if($time_to) {
            $wery->where('orders.created', '<=', $time_to);
        }
        $wery->where('orders.status',4);
        $wery->groupBy('cate_id');
        $wery->orderBy('total_items','desc');
        $wery->limit($limit);

        return $wery->get();
    }
}

