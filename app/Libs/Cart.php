<?php
namespace App\Libs;

use App\Models\Product;
use App\Models\ShopCart;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;

class Cart{
    protected static $instance;
    protected $cart;
    protected $key = 'ShopCart';
    public static $key_chat_app = 'Cart_from_chat_bot';
    protected $customer_id = 0;
    public static $limitPerItem = 100;

    public function __construct($type = 'cart'){
        $this->key .= '_'.$type;
        if(\Auth::guard('customer')->check()){
            $this->customer_id = \Auth::guard('customer')->id();
        }
        $this->restore();
    }

    public static function getInstance($type = 'cart') {
        if (empty(self::$instance[$type])) {
            self::$instance[$type] = new Cart($type);
        }
        return self::$instance[$type];
    }

    public function add($product_id,$filter_key, $product_name, $quantity = 1, $price = 0, $options = null, $replace_quantity = false){
        if($quantity <= self::$limitPerItem) {
            $data = ['name' => $product_name, 'quan' => (int)$quantity, 'price' => $price, 'opt' => $options];
            $details = $this->cart->get('details');
//            if ($details->has($product_id)) {
            $existed = $this->checkingExisted($product_id,$filter_key);
//            dd($product_id);
            if($existed !== false){
                $result = $this->updateDetail($existed, $data, $replace_quantity);
                if(!$result){
                    return 0;
                }
            } else {
                $data['id'] = $product_id;
                $data['filter_key'] = $filter_key;
//                $details->put($product_id, $data);
                $details->push($data);
            }
            $this->refresh();

            //store
            $this->store();

            return true;
        }
        return false;
    }

    public function updateWallet($id){
        $this->cart->put('ewallet_id', $id);
        $this->store();
    }

    public function isEmpty(){
        return $this->cart->get('number') == 0 && $this->cart->get('total') == 0;
    }

    public function remove($filter_key = '',$product_id = ''){

        $existed = $this->checkingExisted($product_id,$filter_key);

        if($existed !== false) {
            $this->cart->get('details')->forget($existed);
            $this->refresh();

            $this->store();
        }
    }

    public function get($key = '', $product = true){
        if($product) {
            return $this->cart->get('details')->get($key);
        }
        return $this->cart->get($key);
    }

    public function toArray(){
        return $this->cart->toArray();
    }

    public function content($imgSize = 'small',$withInfo = false){

//        $this->destroy();
        $data = $this->toArray();
//        dd($data);
        if(!empty($data['details'])){

            if($withInfo) {
                $infor = Product::whereIn('id', $data['itm_ids'])->get()->keyBy('id')->toArray();
            }
            $cus = Auth::guard('customer');
//            $check = $cus->check();
//            $percent = Customer::getDiscount($cus->user());
            foreach ($data['details'] as $k => $v){
                if(!empty($v['opt'])) {
                    if (empty($v['opt']['img'])) {
                        $v['opt']['img'] = '';
                    }
                    $v['link'] = route('product.detail', ['safe_title' => Str::slug($v['name']),'id' => $v['id']]);
                    $v['opt']['img_or'] = $v['opt']['img'];
                    $v['opt']['img'] = Product::getImage($v['opt']['img'], $imgSize);
                    if (!empty($v['opt']['exp'])) {
                        $v['opt']['exp'] = \Lib::dateFormat($v['opt']['exp'], 'd/m/Y');
                    }
                    if (empty($v['opt']['price_dl'])){
                        $v['opt']['price_dl'] = 0;
                    }

//                    if (!empty($check)){
//                        $price_dl = $v['price'] - ($v['price']/100*$percent);
//                    }
//                    $v['opt']['price_dl'] = @$price_dl;

                }

                $data['details'][$k] = $v;

            }
            $data['pass_min_order'] = self::total() > \Lib::getSiteConfig('min_order') ? 1: 0;
            $data['shipping_fee'] = (int)@\Lib::getSiteConfig('shipping_fee') ?? 0;
        }
//        dd($data);
        return $data;
    }

    public function destroy($type = 'cart'){
        $this->removeCookie();
        $customer_id = $this->get('customer_id', false);
        if($customer_id > 0){
            $shopCart = ShopCart::where('customer_id', $customer_id);
            if($shopCart){
                $shopCart->delete();
            }
        }
        self::$instance[$type] = null;
        unset(self::$instance[$type]);
    }

    public function total(){
        return $this->cart->get('total');
    }

    public function count(){
        return $this->cart->get('number');
    }

    public function search(){

    }

    public function restore(){
        $cart = [];
        $customer_id = $this->customer_id;
        $store = false;

        //lay tu cookie truoc
//        $tmp = Cookie::get($this->key, []);
        $tmp = session($this->key);
        if(!empty($tmp) && $tmp != '%5B%5D'){
            $tmp = json_decode($tmp,1);
            if(!empty($tmp)) {
                $cart = $this->dataCart(
                    isset($tmp['details']) ? $tmp['details'] : [],
                    isset($tmp['itm_ids']) ? $tmp['itm_ids'] : [],
                    isset($tmp['total']) ? $tmp['total'] : 0,
                    isset($tmp['number']) ? $tmp['number'] : 0,
                    isset($tmp['ewallet_id']) ? $tmp['ewallet_id'] : 0,
                    isset($tmp['customer_id']) ? $tmp['customer_id'] : 0,
                    isset($tmp['shipping_fee']) ? $tmp['shipping_fee'] : 0
                );
                if($customer_id > 0){
                    $store = true;
                }
            }
        }
        //neu ko co cookie thi uu tien lay tu khach hang neu dang dang nhap
        if(empty($cart) && $customer_id > 0) {
            $tmp = ShopCart::where('customer_id', $customer_id)->first();
            if ($tmp && !empty($tmp->details)) {
                $details = json_decode($tmp->details, 1);
                if (!empty($details)) {
                    $cart = $this->dataCart($details,$tmp->itm_ids, $tmp->total, $tmp->number, $tmp->ewallet_id, $tmp->customer_id);
                }
            }
        }
        if(empty($cart)){
            $cart = $this->dataCart();
        }
        $this->cart = $cart;
        $this->refresh();
        if($store){
            $this->cart->put('customer_id', $customer_id);
            $this->store();
        }
    }

    public function store(){
        $customer_id = $this->customer_id;
        if($customer_id > 0){
            $this->cart->put('customer_id', $customer_id);
            $cart = ShopCart::where('customer_id', $customer_id)->first();
            if(empty($cart)){
                $cart = new ShopCart();
            }
            $cart->customer_id =  $customer_id;
            $cart->total =  $this->cart->get('total');
            $cart->number =  $this->cart->get('number');
            $cart->ewallet_id =  $this->cart->get('ewallet_id');
            $cart->details =  json_encode($this->cart->get('details')->toArray());
            $cart->itm_ids =  json_encode($this->cart->get('itm_ids'));
            $cart->save();

            //clear cookie neu co
            $this->removeCookie();
        }else{
            $cart = $this->cart->toArray();
//            Cookie::queue($this->key, json_encode($cart), 60*24*365);
            session([$this->key => json_encode($cart)]);
        }
    }

    protected function removeCookie(){
//        Cookie::queue($this->key, '', 60*24*365);
//        Cookie::forget($this->key);
        session()->forget($this->key);
    }

    public function checkingExisted($product_id,$filter_key) {
        $details = $this->cart->get('details');
        foreach($details as $idx => $itm) {
            if(($itm['id'] == $product_id && $itm['filter_key'] == $filter_key)
                || ($itm['id'] == $product_id && $itm['filter_key'] == '')){
                return $idx;
            }
        }
        return false;
    }

    protected function refresh(){
        $total = 0;
        $number = 0;
        $itm_ids = [];
        $details = $this->cart->get('details');
        $ship_fee = isset($this->cart->shipping_fee) ? $this->cart->shipping_fee : 0;
        if(!empty($details)){
            $total += $ship_fee;
            foreach ($details as $item){
                $total += $item['price'] * $item['quan'];

                $number+= $item['quan'];
                $itm_ids[] = $item['id'];
            }
        }
        $this->cart->put('details',collect(array_values($details->toArray())));
        $this->cart->put('total', $total);
        $this->cart->put('number', $number);
        $this->cart->put('itm_ids', $itm_ids);
    }

    protected function dataCart($details = [],$itm_ids = [], $total = 0, $number = 0, $ewallet_id = 0, $customer_id = 0,$shipping_fee = 0){
        if(empty($details)){
            $details = [];
        }
        return collect(['details' => collect($details),'itm_ids' => $itm_ids, 'total' => $total, 'number' => $number, 'ewallet_id' => $ewallet_id, 'customer_id' => $customer_id,'shipping_fee' => $shipping_fee]);
    }

    protected function updateDetail($idex = 0, $data, $replace_quantity = false){
        $details = $this->cart->get('details');
        $product = $details->get($idex);
        if($product){
            if(isset($data['id'])){
                unset($data['id']);
            }
            foreach ($data as $k => $v){
                switch ($k){
                    case 'quan':
                        if($replace_quantity){
                            $product[$k] = $v;
                        }else{
                            $product[$k] += $v;
                        }
                        if($product[$k] > self::$limitPerItem){
                            return false;
                        }
                        break;
                    case 'opt':
//                        if(!empty($v) && is_array($v)){
//                            if(!empty($product[$k]['opt']) && is_array($product[$k]['opt'])){
//                                foreach ($v as $i => $t){
//                                    $product[$k]['opt'][$i] = $t;
//                                }
//                            }else{
                                $product[$k] = $v;
//                            }
//                        }
                        break;
                    default:
                        $product[$k] = $v;
                }
            }
            $details->put($idex, $product);

            return true;
        }
        return false;
    }
}