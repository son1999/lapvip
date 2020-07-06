<?php
/**
 * Created by PhpStorm.
 * Filename: CheckoutController.php
 * User: Thang Nguyen Nhan
 * Date: 23-Jul-19
 * Time: 10:31
 */

namespace App\Modules\FrontEnd\Controllers;


namespace App\Modules\FrontEnd\Controllers;

use App\Libs\BizPay;
use App\Libs\CouponLib;
use App\Models\Customer;
use App\Models\Feature;
use App\Models\Filter;
use App\Models\GeoProvince;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Validator;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\GeoDistrict;
use App\Libs\Cart;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use DB;

class CheckoutController extends Controller
{
    public static $KEY_CUS_INFOR = 'cart_infor_';
    public static $KEY_CONFIRM_INFOR = 'cart_confirm_infor_';

    public function __construct()
    {
        \Lib::addBreadcrumb();
    }

    public function cart(Request $request)
    {
        \Lib::addBreadcrumb(__('site.giohang'));
        $content = Cart::getInstance()->content();
        $coupon_code = $request->coupon_code;
        if ($coupon_code){
            $return_coupon = CouponLib::calcCoupon($coupon_code,$content['total'],$content);
        }
        $content['grand_total'] = isset($return_coupon['grand_total']) && $return_coupon['grand_total'] > 0 ? $return_coupon['grand_total'] : $content['total'];

        if($content['grand_total'] > @\Lib::getSiteConfig('free_ship')) {
            $content['shipping_fee'] = 0;
        }
        $content['grand_total'] += $content['shipping_fee'];
        $dataPassing = [
            'site_title' => __('site.giohang'),
            'cart' => [],
            'first_title' => __('site.giohang'),
            'slide' => Feature::getSlideByPositions('vi','cart')->limit(6)->get(),
            'maybe_interest_foods' => Product::maybeInteresting(),
            'list_filter' => [],
            'done' => 1,
            'coupon_code' => @$coupon_code,

        ];
        if (!empty($content) && $content['number'] > 0) {

            $view = 'FrontEnd::pages.checkout.cart';
            $dataPassing['cart'] = $content;
        } else {
            $view = 'FrontEnd::pages.checkout.cart';
        }
        return view($view, $dataPassing);
    }

    public function saveInfo(Request $request) {
        $content = Cart::getInstance()->content();
        if(!empty($content) && $content['number'] > 0) {
            $valid = [
                'pay_method' =>'required',
                'name' => 'required',
                'phone' => ['required', 'numeric', 'regex:/^(03|09|06|08|07|05)+([0-9]{8})$/'],
                'email' => 'nullable|email',
                'provinces' => 'required',
                'districts' => 'required',
                'address' => 'required'
            ];
            $messages = [
                'pay_method' => 'Trường Hình thức thanh toán không được để trống',
                'name.required' => 'Trường Họ và tên không được để trống',
                'phone.required' => 'Trường Số điện thoại không được để trống',
                'phone.numeric' => 'Trường Số điện thoại không đúng định dạng',
                'phone.regex' => 'Trường Số điện thoại không đúng định dạng',
                'email.email' => 'Trường Email không đúng định dạng',
                'provinces.required' => 'Trường Thành phố không được để trống',
                'districts.required' => 'Trường Quận, Huyện không được để trống',
                'address.required' => 'Trường Địa chỉ không được để trống',
            ];
            $validator = Validator::make($request->all(), $valid, $messages);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {

                $coupon_code = $request->coupons_code;
                if (!empty($coupon_code)){
                    $return_coupon = CouponLib::calcCoupon($coupon_code,$content['total'],$content);
                    $content['gr_total'] = isset($return_coupon['gr_total']) && $return_coupon['gr_total'] > 0 ? $return_coupon['gr_total'] : $content['total'];
                    $content['dccoupon'] = isset($return_coupon['dccoupon']) && $return_coupon['dccoupon'] > 0 ? $return_coupon['dccoupon'] : 0;
                    if($content['gr_total'] > @\Lib::getSiteConfig('free_ship')) {
                        $content['shipping_fee'] = 0;
                    }
                    $content['gr_total'] += $content['shipping_fee'];
                }
                $type = 'order';
                $data = new Order();
                $data->type = $type;
                $data->created = time();
                $data->fullname = $request->name;
                $data->phone = $request->phone;
                $data->email = @$request->email;
                $data->province_id = $request->provinces;
                $data->district_id = $request->districts;
                $data->payment_type = $request->pay_method;
                $data->total_price = isset($return_coupon['gr_total']) && $return_coupon['gr_total'] > 0 ? $return_coupon['gr_total'] : $content['total'];
                $data->fee_shipping = $content['shipping_fee'];
                $data->address = $request->address;
                $data->payment_type = $request->pay_method;
                $data->order_from = isset($request->chat_app) ? 'chat' : 'web';
                $data->coupon_code = isset($return_coupon['gr_total']) && $return_coupon['gr_total'] > 0 ? $coupon_code : '';
                $data->coupon_value = isset($return_coupon['gr_total']) && $return_coupon['gr_total'] > 0 ? $return_coupon['dccoupon'] : 0;
                $data->status = 1;
                try {
                    $data->save();
                    $data->code = Order::makeCode($type, $data->id,\Lib::getDefaultLang());
                    if($type == 'order') {
                        $data->items()->saveMany(OrderItem::returnOrderItemObjs($content['details'], $data->id));
                    }
                    $data->token_tracking = Order::genTokenTracking($data->code);
                    $data->save();

                }catch(\Exception $e){
                    throw $e;
                }

                Cart::getInstance()->destroy();
                $request->session()->remove(self::$KEY_CUS_INFOR);
                $params = ['token' => $data->token_tracking];

                return redirect(route('cart.checkout.cart_complete', $params));
            }
        }
    }

    public function chooseTypePayment(Request $request){
        \Lib::addBreadcrumb(__('site.giohang'),route('cart'));
        \Lib::addBreadcrumb(__('site.thanhtoan'));
        $content = Cart::getInstance()->content();
        $cus_infor = $request->session()->get(self::$KEY_CUS_INFOR);
//        dd($cus_infor);

        if(isset($cus_infor['coupon_code'])) {
            $coupon_code = $cus_infor['coupon_code'];
            $return_coupon = CouponLib::calcCoupon($cus_infor['coupon_code'],$content['total'],$content);
        }
        $content['grand_total'] = isset($return_coupon['grand_total']) && $return_coupon['grand_total'] > 0 ? $return_coupon['grand_total'] + $content['shipping_fee'] : $content['total'];

        if(!empty($content) && $content['number'] > 0) {
            $products = Product::whereIn('id', $content['itm_ids'])->get()->keyBy('id');
            return view('FrontEnd::pages.checkout.choose_type', [
                'site_title' => 'Giỏ hàng',
                'done' => 2,
                'products' => $products,
                'cart' => $content,
                'province' => GeoProvince::find($cus_infor['province']),
                'district' => GeoDistrict::find($cus_infor['district']),
                'payment_types' => Order::paymentType(),
                'first_title' => 'Thông tin thanh toán',
                'cus_infor' => $cus_infor,
                'coupon_code' => @$coupon_code,
                'dccoupon' => @$return_coupon['dccoupon'],
//                'banks'=>Bank::getBankByLang(\Lib::getDefaultLang()),
                'request' => $request,
            ]);
        }else {
            return redirect('checkout/cart');
        }
    }

    public function error(Request $request){
        \Lib::addBreadcrumb(__('site.giohang'),route('cart'));
        \Lib::addBreadcrumb('Thông báo lỗi');
        $order = Order::getOrderByCode($request->order_code);
        return view('FrontEnd::pages.checkout.payment_error', [
            'site_title' => 'Thông báo lỗi',
            'order'=>$order,
            'payment_types' => Order::paymentType(),
            'err'=>$request->err
        ]);
    }

    public function destroy(Request $request){
        Cart::getInstance()->destroy();

        $is_booking_table = $request->is_booking_table;
        $token_tracking = $request->token_tracking;

        if($is_booking_table) {
//            $booking = Order::getByTokenTracking($request->token_tracking);
            return redirect()->route('lands.cart.index');
        }
        return redirect()->route('lands.index');
    }

    public function cart_post(Request $request) {
        $type = $request->get('type');
//        $cus_note = $request->get('cus_note');
        $data = $request->session()->get(self::$KEY_CUS_INFOR);
//        $data['cus_note'] = $cus_note;
        $request->session()->put(self::$KEY_CUS_INFOR,$data);
        return redirect(route('cart_infor'));
    }

    public function cart_infor(Request $request) {
        \Lib::addBreadcrumb(__('site.giohang'),route('cart'));
        \Lib::addBreadcrumb(__('site.thanhtoan'));
        $content = Cart::getInstance()->content();
        $customer = \Auth::guard('customer')->user();
        $cus_infor = $request->session()->get(self::$KEY_CUS_INFOR);

        if(!empty($content) && $content['number'] > 0) {
            if($request->input('coupon_code')) {
                $coupon_code = $request->input('coupon_code');
                $return_coupon = CouponLib::calcCoupon($coupon_code,$content['total'],$content);
            }

//            dd($return_coupon);

            $content['grand_total'] = isset($return_coupon['grand_total']) && $return_coupon['grand_total'] > 0 ? $return_coupon['grand_total'] + $content['shipping_fee'] : $content['total'];

            $products = Product::whereIn('id', $content['itm_ids'])->get()->keyBy('id');
            return view('FrontEnd::pages.checkout.infor', [
                'site_title' => __('site.giohang'),
                'first_title' => __('site.thanhtoan'),
                'products' => $products,
                'cart' => $content,
                'list_provinces' => GeoProvince::getAll(),
                'list_districts' => GeoDistrict::getListDistrictsByCity(@$cus_infor['province'] ?? @$request->old('province') ?? @$customer->province_id),// Mặc định là HÀ NỘI
                'cus_infor' => @$cus_infor,
                'customer' => $customer,
                'payment_types' => Order::paymentType(),
                'shipping_notices' => Order::shippingNotices(),
                'coupon_code' => @$coupon_code,
                'dccoupon' => @$return_coupon['dccoupon'],
//                'type_address' => AddressBook::getType(),
//                'bank_accounts' => Bank::getBankByLang(\Lib::getDefaultLang()),
                'done' => 1,

            ]);
        }else {
            return redirect('checkout/cart');
        }
    }

    public function success_online(Request $request) {
        $order = isset($request->order_code) && isset($request->hash) ? Order::getOrderByCode($request->order_code) : '';
        if($order && $order->payment_type == 1 && $order->payment_time == 0) {

            $err = '';
            $bizpay = new BizPay();
            $isSuccess = $bizpay->verifyUrlCallback($err);
            $order->wepay_order_session = $request->secure_hash;
            $order->payment_time = $request->created_order_date;
            $order->wepay_type = $request->gate;
            if($isSuccess){
                $order->status = 4;
                $order->payment_time = time();
                $order->payment_status = 1;
                $err = 'Thanh toán thành công';
            }else{
                /*Hủy đơn*/
                $order->payment_status = 0;
                $order->status = -1;
            }
            /*log thanh toán*/
            OrderLog::add($order->id,'payment',$err);

            try{
                $order->save();
            }catch(\Exception $e) {
                $errors = $e;
            }

            if($order->status == 4) {
                event('order.success',[$order->id,$order]);
                event('order.payment',[$order->id,$order]);

                return redirect()->route('cart_complete', ['token' => $order->token_tracking]);
            }else {
                return redirect()->route('cart.checkout.err',array('order_code'=>$order->code));
            }
        }
        abort(404);
    }

    public function cart_complete(Request $request) {
        $token_tracking = $request->get('token');
        \Lib::addBreadcrumb(__('site.giohang'),route('cart.checkout.cart'));
        \Lib::addBreadcrumb(__('site.hoanthanh'));

        $order = isset($request->order_code) && isset($request->error_code) ? Order::getOrderByCode($request->order_code) : Order::getByTokenTracking($token_tracking);
        if($order && $order->type == 'order') {
            $ad_confirm = $request->get('ad_confirm');
            $code = $request->get('code');
            $view = 'FrontEnd::pages.checkout.complete';
            $dataPassing = [
                'site_title' => __('site.trangchu'),
                'first_title' => 'Hoàn thành đơn hàng',
                'slide' => Feature::getSlideByPositions('vi','cart')->limit(6)->get(),
                'order' => $order,
                'method' => Order::METHOD,
                'payment_types' => Order::paymentType(),
                'shipping_notices' => Order::shippingNotices(),
                'is_chat_app' => isset($request->chat_app) ? 1 : 0,
                'done' => 3
            ];

            return view($view, $dataPassing);
        }
        abort(404);
    }

    public function cart_complete_post(Request $request) {


        $content = Cart::getInstance()->content();
        $customer = \Auth::guard('customer')->user();
        if(!empty($content) && $content['number'] > 0) {

            $valid = [
                'payment_type' => 'required|numeric',
//                'bank_id' => ['required_if:payment_type,==,2','numeric'],
//                'option_payment' => ['required_if:payment_type,==,1'],
//                'bankcode' => ['required_if:option_payment,==,ATM_ONLINE']
            ];
            $this->validate($request, $valid,
                [
                    'payment_type.required' => __('site.chonhinhthucthanhtoan'),
                    'payment_type.numeric' => __('site.chonhinhthucthanhtoan'),
//                    'bank_id.required_if' => __('site.haychonnganhang'),
//                    'bank_id.numeric' => __('site.haychonnganhang'),
//                    'option_payment.required_if' => __('site.haychonnganhang'),
//                    'bankcode.required_if' => 'Hãy chọn ngân hàng bạn muốn thanh toán',
                ]
            );

            $cus_infor = $request->session()->get(self::$KEY_CUS_INFOR);
            $cus_infor['bank_id'] = $request->payment_type == 1 ? $request->bank_id : 0;
            $request->session()->put(self::$KEY_CUS_INFOR, $cus_infor);

            $data = new \stdClass();
            $data->fullname = $cus_infor['fullname'];
            $data->phone = $cus_infor['phone'];
            $data->email = $cus_infor['email'];
            $data->province_id = $cus_infor['province'];
            $data->district_id = $cus_infor['district'];
            $data->note = $cus_infor['cus_note'];
            $data->total_price = $content['total'];
            $data->shipping_fee = $content['shipping_fee'];
            $data->address = $cus_infor['address'];
            $data->payment_type = $request->payment_type;
            $data->bank_id = isset($request->bank_id) && $request->bank_id ? $request->bank_id : 0;
            $data->customer_id = isset($customer->id) ? $customer->id : 0;
            $data->order_from = isset($request->chat_app) ? 'chat' : 'web';
            $data->booking_items = $content['details'];
            $data->coupon_code = @$cus_infor['coupon_code'];
//dd($data);
            $order = Order::createOrder($data,'order',$content);
            if(null !== $order) {
                if ($order->payment_type == 1) {
                    if($order->status==1){
                        $total_price_cart = $order->total_price + $order->fee_shipping;

                        $transaction_info = 'Thanh toán đơn hàng #' . $order->code . '; ';
                        $transaction_info .= 'Tổng tiền: ' . \Lib::priceFormatEdit($total_price_cart)['price'].'<sup class="text-danger">đ</sup>' . '; ';
                        $transaction_info .= 'Họ tên: ' . $order->fullname . ' - SĐT: ' . $order->phone;
                        //Build URL trả về từ Wepay
                        $params = array('order_code' => $order->code);
                        $params['hash'] = \Lib::getHash('sha256',$order->code,$order->id);
                        $return_url = route('cart.payment.success', $params);
                        $method = '';

                        $classPayment = new BizPay();
                        $response = $classPayment->buildUrlCheckout(
                            array(
                                'order_id'=>$order->code,
                                'order_value'=>$total_price_cart,
                                'email'=>$order->email,
                                'redirect_url'=>$return_url,
                            )
                        );
                        if(!empty($response)){
                            $response = json_decode($response,1);
                            if (isset($response['success']) && $response['success'] && $response['data']['redirect']) {
                                Cart::getInstance()->destroy();
                                $request->session()->remove(self::$KEY_CUS_INFOR);
                                return redirect($response['data']['redirect']);
                            } else {
                                return redirect()->route('cart.checkout.err',array('order_code'=>$order->code,'err'=>$response['message']));
                            }
                        }
                    }else{
                        $err = 'Thanh toán lỗi!';
                        return redirect()->route('cart.checkout.err',array('order_code'=>$order->code,'err'=>$err));
                    }
                }else {
                    $order->status = 1;

//                    $order->bank = isset($order->bank_id) ? Bank::find($order->bank_id) : 0;
                    event('order.success',[$order->id,$order]);

                    Cart::getInstance()->destroy();
                    $request->session()->remove(self::$KEY_CUS_INFOR);
                    $params = ['token' => $order->token_tracking];

                    return redirect(route('cart_complete', $params));
                }
            }
        }
        return redirect('checkout/cart');
    }
}