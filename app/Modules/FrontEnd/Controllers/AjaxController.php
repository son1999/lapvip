<?php

namespace App\Modules\FrontEnd\Controllers;

use App\Libs\Cart;
use App\Libs\CouponLib;
use App\Models\Comment;
use App\Models\Customer;
use App\Models\Filter;
use App\Models\GeoDistrict;
use App\Models\GeoWard;
use App\Models\InstallmentBank;
use App\Models\InstallmentDetail;
use App\Models\InstallmentScenarios;
use App\Models\InstallmentSuccess;
use App\Models\Product;
use App\Models\ProductDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Validator;
//custom models
use App\Models\Subscriber;
use Illuminate\Support\Facades\Auth;


class AjaxController extends Controller
{
    public function __construct()
    {
        //
    }

    public function init(Request $request, $cmd){
        $data = [];
        switch ($cmd) {
            case 'login':
                $data = $this->login($request);
                break;
            case 'register':
                $data = $this->register($request);
                break;
            case 'change-password':
                $data = $this->changePassword($request);
                break;
            case 'email-active':
                $data = $this->emailActive($request);
                break;
            case 'subscribe':
                $data = $this->subscribe($request);
                break;
            case 'route':
                $data = $this->updateRoute($request);
                break;
            case 'cart-load':
                $data = $this->cartShow($request);
                break;
            case 'cart-number':
                $data = $this->cartLoadNumber($request);
                break;
            case 'cart-add':
                $data = $this->cartAdd($request);
                break;
            case 'cart-update':
                $data = $this->cartUpdate($request);
                break;
            case 'cart-remove':
                $data = $this->cartRemove($request);
                break;
            case 'list-districts':
                $data = $this->listDistricts($request);
                break;
            case  'list-ward' :
                $data = $this->listWards($request);
                break;
            case 'check-coupon':
                $data = $this->checkCoupon($request);
                break;
            case 'search':
                $data = $this->search($request);
                break;
            case 'searchForm':
                $data = $this->searchForm($request);
                break;
            case 'comment':
                $data = $this->commentproduct($request);
                break;
            case 'installment':
                $data = $this->installment($request);
                break;
            case 'loadInstallmentScenariosByID':
                $data = $this->loadInstallmentScenariosByID($request);
                break;
            case 'loadPaymentByBankID':
                $data = $this->loadPaymentByBankID($request);
                break;
            case 'savePaymentByBankID':
                $data = $this->savePaymentByBankID($request);
                break;
            case 'searchProductCompare':
                $data = $this->searchProductCompare($request);
                break;
            case 'searchProductInstallment':
                $data = $this->searchProductInstallment($request);
                break;
            case 'loadMoreAjax':
                $data = $this->loadMoreAjax($request);
                break;
            case 'loadMoreFilterAjax':
                $data = $this->loadMoreFilterAjax($request);
                break;
            case 'loadMoreSearchAjax':
                $data = $this->loadMoreSearchAjax($request);
                break;
            case 'getDataP':
                $data = $this->ajaxgetDataP($request);
                break;
            default:
                $data = $this->nothing();
        }
        return response()->json($data);
    }
    public function checkCoupon(Request $request){
        if(!empty($request->coupon)){
            $giftLib = new CouponLib();
            $cart_content = Cart::getInstance()->content();
            $checking['category'] = Product::getAllCateFromPrdIds($cart_content['itm_ids']);
            $checking['product'] = $cart_content['itm_ids'];
            $coupon = $giftLib->checkCouponSys($request->coupon,$checking);
            if(!empty($coupon)) {
                return \Lib::ajaxRespond(true, 'Available', $coupon);
            }
        }
        return \Lib::ajaxRespond(false,"Mã coupon không hợp lệ hoặc đã được sử dụng");
    }
    public function listWards($request) {
        $district_id = $request->get('district_id');
        $data = GeoWard::getListwards($district_id);
//        dd($data->toArray());
        if(!empty($data)) {
            return \Lib::ajaxRespond(true, 'success', $data);
        }
    }

    public function listDistricts($request) {
        $province_id = $request->get('province_id');
        $data = GeoDistrict::getListDistrictsByCity($province_id);
        if(!empty($data)) {
            return \Lib::ajaxRespond(true, 'success', $data);
        }
    }

    public function cartShow(Request $request){
        $content = Cart::getInstance()->content();

        $coupon_code = request()->coupons_code;
        if (!empty($coupon_code)){
            $return_coupon = CouponLib::calcCoupon($coupon_code,$content['total'],$content);
            $content['gr_total'] = isset($return_coupon['gr_total']) && $return_coupon['gr_total'] > 0 ? $return_coupon['gr_total'] : $content['total'];
            $content['dccoupon'] = isset($return_coupon['dccoupon']) && $return_coupon['dccoupon'] > 0 ? $return_coupon['dccoupon'] : 0;
            if($content['gr_total'] > @\Lib::getSiteConfig('free_ship')) {
                $content['shipping_fee'] = 0;
            }
            $content['gr_total'] += $content['shipping_fee'];
//            return \Lib::ajaxRespond(true, 'success', $this->cartMixConent($content));
            return \Lib::ajaxRespond(true, 'success', $content);
        }
        return \Lib::ajaxRespond(true, 'success', $content);
    }

    public function cartLoadNumber(Request $request){
        $content = Cart::getInstance()->content();
        return \Lib::ajaxRespond(true, 'success', $content['number']);
    }

    public function cartRemove(Request $request){
        Cart::getInstance()->remove($request->filter_key,$request->id);
        $content = Cart::getInstance()->content();
        return \Lib::ajaxRespond(true, 'success', $this->cartMixConent($content));
    }

    public function cartChangePackage(Request $request){
        Cart::getInstance()->remove($request->old_id);
        $return = $this->cartAdd($request,false,true);
        $content = Cart::getInstance()->content();
        return \Lib::ajaxRespond(true, 'success', $this->cartMixConent($content));
    }

    public function cartUpdate(Request $request){
        return $this->cartAdd($request, true);
    }

    public function cartAdd(Request $request, $replace = false,$call_only = false){


        if($replace) {
            $item = Cart::getInstance()->get($request->index);
            $product = Product::getByPriceFilterKey(@$item['id'],$request->filter_key,$request->quan);
        }else {
            $existed = Cart::getInstance()->checkingExisted($request->id,$request->filter_key);
            if($existed !== false) {
                $item = Cart::getInstance()->get($existed);
                $current_quan = $item['quan'];
            }

            $product = Product::getByPriceFilterKey($request->id,$request->filter_key,$request->quan + ( isset($current_quan) && $current_quan ? $current_quan : 0));
        }
        if($product){
            $msg = '';
            if($replace){
                $productCart = 0;
            }else {
                $productCart = Cart::getInstance()->get($request->filter_key);
                $productCart = (!empty($productCart) && !empty($productCart['quan'])) ? $productCart['quan'] : 0;
            }

            $cus = Auth::guard('customer');
            if (!empty($cus->check())){
                $customer_gp = Customer::with('groups' )->where('id', $cus->user()->id)->first();
                $percent = $customer_gp['groups']->max('percent');
                $price_dl = $product->price - ($product->price/100*$percent);
            }
            $opt = [

//                    'mid' => $food->merchant_id,
                'po' => isset($product->price_strike) ? $product->price_strike : $product->priceStrike,
                'price_dl' => @$price_dl ,
                'img' => $product->image,
//                    'per' => $food->percent,
//                    'exp' => $food->expired
            ];

            $filters = Filter::getWithCate(explode(',',$request->filter_key));
            if(!empty($filters)) {
                foreach($filters as $filter){
                    $temp = [
                        'filter_cate_title' => $filter->filter_cate->title,
                        'filter_value' => $filter->title
                    ];
                    $opt['meta'][] = $temp;
                }
            }

            if(!empty($request->opt) && is_array($request->opt)) {
                foreach($request->opt as $k => $v){
                    $opt[$k] = $v;
                }
            }


                $result = Cart::getInstance()->add($product->id,$request->filter_key, $product->title, $request->quan, $product->price, $opt, $replace);


            if($result === true) {
                if($call_only) {
                    return true;
                }else {
                    $content = Cart::getInstance()->content();
//                    dd($content);
                    if ($replace == true){
                        return \Lib::ajaxRespond(true, 'success', $content);
                    }else{
                        return \Lib::ajaxRespond(true, 'success', ['url' => route('cart.checkout.cart')]);
                    }
                }
            }else {

                $msg = $result === 0 ? __('site.khongtimthaythongtinsanpham') : __('site.soluongvuotqua').': '.Cart::$limitPerItem;
            }
//                return \Lib::ajaxRespond(false, 'Mỗi sản phẩm chỉ cho phép mua tối đa '.Cart::getInstance()->limitVoucher().' ecash/lần');
//            }
            if($call_only) {
                return true;
            }else {
                $content = Cart::getInstance()->content();
                return \Lib::ajaxRespond(false, $msg, $this->cartMixConent($content));
            }
        }
        if($call_only) {
            return true;
        }else {
            return \Lib::ajaxRespond(false, 'Sản phẩm này không còn đủ hàng số lượng bạn muốn mua!');
        }
    }

    public function updateRoute(){
        $routes = \Lib::saveRoutes();
        return \Lib::ajaxRespond(true, 'ok', $routes);
    }

    public function subscribe(Request $request){
        $subscriber = Subscriber::where('email', $request->email)->first();
        if(empty($subscriber)){
            $subscriber = new Subscriber();
            $subscriber->email = $request->email;
            $subscriber->created = time();
        }
        $customer = Customer::where('email', $request->email)->first();
        if(!empty($customer)){
            $subscriber->customer_id = $customer->id;
        }
        $subscriber->save();
        return \Lib::ajaxRespond(true, __('site.camonbandadangkyyeucaucuabandaduocghinhan'));
    }

    public function emailActive(Request $request){
        $customer = Customer::find($request->id);
        if($customer){
            $customer->token->newToken();

            event('customer.register.resend', $customer->id);

            return \Lib::ajaxRespond(true, 'success');
        }
        return \Lib::ajaxRespond(false, 'Người dùng không tồn tại hoặc đã bị khóa');
    }

    public function register(Request $request){
        $validate = \Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:customers,email',
                'password' => 'required|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%@]).*$/|confirmed',
                'provinces' => 'required',
                'districts' => 'required',
                'icheck' => 'required',
            ],
            [
                'regis_name.required' => 'Chưa nhập Họ và Tên',
                'regis_name.unique' => 'Họ và Tên đã được sử dụng',
                'email.required' => 'Chưa nhập Email',
                'email.unique' => 'Email của bạn đã được sử dụng',
                'email.email' => 'Định dạng Email không đúng',
                'password.required' => 'Chưa nhập mật khẩu',
                'password.min' => 'Mật khẩu phải có từ 8 kí tự trở lên',
                'password.regex' => 'Mật khẩu phải bao gồm chữ, số và kí tự đặc biệt (!, $, #, %, @)',
                'password.confirmed' => 'Xác nhận mật khẩu sai',
                'provinces.required' => 'Chưa chọn Tỉnh/Thành phố',
                'districts.required' => 'Chưa chọn Quận/Huyện',
                'icheck.required' => 'Bạn chưa đồng ý với điều khoản của chúng tôi',
            ]
        );
        if ($validate->fails()) {
            return \Lib::ajaxRespond(false, 'error', $validate->errors()->all());
        }elseif(\Lib::isWeakPassword($request->password)){
            return \Lib::ajaxRespond(false, 'error', ['Mật khẩu quá yếu']);
        }

        $customer = Customer::where('email', $request->email)->first();
        if($customer){
            return \Lib::ajaxRespond(false, 'error', 'EXISTED');
        }
        $customer = Customer::createOne($request);


//        Auth::guard('customer')->login($customer);

        //gui email
        event( "customer.register", $customer->id);
        return \Lib::ajaxRespond(true, 'success', ['url' => route('register.success').'?email='.$customer->email]);
//        $this->guard()->login($customer);
//        return \Lib::ajaxRespond(true, 'success', ['url' => route('customer.active')]);
//        return redirect()->route('user.index');
    }

    public function login(Request $request){

        if(!\Auth::guard('customer')->check()){
            $customer = Customer::where('email', $request->email)->first();
            if(!empty($customer)) {
                if($customer->active > 0) {
                    if($customer->status > 0) {
                        if (\Auth::guard('customer')->attempt(['email' => $request->email, 'password' => $request->password])) {
                            $request->session()->regenerate();
                            return \Lib::ajaxRespond(true, 'success', ['url' => route('home')]);
                        }
                        return \Lib::ajaxRespond(false, 'error', 'LOGIN_FAIL');
                    }
                    return \Lib::ajaxRespond(false, 'error', 'BANNED');
                }
                return \Lib::ajaxRespond(false, 'error', 'NOT_ACTIVE');
            }
            return \Lib::ajaxRespond(false, 'error', 'NOT_EXISTED');
        }
        return \Lib::ajaxRespond(false, 'error', 'LOGINED');
    }

    protected function cartMixConent(&$content = []){
        if (\Auth::guard('customer')->check()) {
            $content['customer_id'] = \Auth::guard('customer')->id();
        } else {
            $content['url_login'] = route('login');
        }
        return $content;
    }

    public function changePassword(Request $request){
        $validate = \Validator::make(
            $request->all(),
            [
                'newPassword' => 'required|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%@]).*$/',
            ],
            [
                'newPassword.required' => 'Chưa nhập mật khẩu',
                'newPassword.min' => 'Mật khẩu phải có từ 8 kí tự trở lên',
                'newPassword.regex' => 'Mật khẩu phải bao gồm chữ, số và kí tự đặc biệt (!, $, #, %, @)',
            ]
        );
        if ($validate->fails()) {
            return \Lib::ajaxRespond(false, 'error', $validate->errors()->all());
        }elseif(\Lib::isWeakPassword($request->password)){
            return \Lib::ajaxRespond(false, 'error', ['Mật khẩu quá yếu']);
        }
        if (!(\Hash::check($request->oldPassword, \Auth::guard('customer')->user()->password))){
            return \Lib::ajaxRespond(false, 'error', ['Mật khẩu hiện tại không đúng']);
//            return session()->flash('error', 'Mật khẩu hiện tại không đúng');
        }

        Customer::changePass($request);

        return \Lib::ajaxRespond(true, 'success', ['url' => route('logout')]);

    }

    public function search(Request $request){
        $valid = [
            'search' => 'required',
        ];
        $messages = [
            'search.required' => 'Bạn ey nói hoài rồi, bạn không nhập thì tìm thế lìn nào được, rảnh háng vkl',

        ];

        $validator = Validator::make($request->all(), $valid, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        else{
            return \Lib::ajaxRespond(true, 'success', ['url' => route('product.search.key').'?key='.$request->search]);
        }
    }
    public function searchForm(Request $request){
        $valid = [
            'search' => 'required',
        ];
        $messages = [
            'search.required' => 'Nhập từ khóa bận cần tìm kiếm ... ',

        ];

        $validator = Validator::make($request->all(), $valid, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        else{
            return \Lib::ajaxRespond(true, 'success', ['url' => route('product.searchForm.key').'?key='.$request->search]);
        }
    }

    public function commentproduct(Request $request){
        $validate = \Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'rate' => 'required',
                'content' => 'required',
            ],
            [
                'name.required' => 'Chưa nhập Họ và Tên',
                'rate.required' => 'Bạn chưa đánh giá',
                'content.required' => 'Chưa nhập nội dung đánh giá',
            ]
        );
        if ($validate->fails()) {
            return \Lib::ajaxRespond(false, 'error', $validate->errors()->all());
        }
        $comment = Comment::pushComment($request);

        $tpl = [];
        $tpl['comment'] =  Comment::getCommentProductById($request->type_id)->orderBy('id', 'DESC')->get();
        if(count($tpl['comment']) > 0) {
            $tpl['rating']['total'] = $tpl['comment']->count('rating');
            $tpl['rating']['total_rate'] = $tpl['comment']->where('aid', '')->count('rating');
            $tpl['rating']['avg'] = Comment::getSumRate($request->type_id);
            // dd($tpl['rating']['avg']);
            $tpl['rating']['avg'] = round($tpl['rating']['avg']/$tpl['rating']['total_rate'], 1);
            for ($i=1; $i <= 5 ; $i++) {
                $count = $tpl['comment']->where('rating', $i)->count('rating');
                // $tpl['rating']['avg'] += $count;
                $tpl['rating']['rating_'.$i] = $count/$tpl['rating']['total']*100;
            }
        }
        $rate = Product::find($request->type_id);
        $rate->rate_avg = $tpl['rating']['avg'];
        $rate->save();

        return \Lib::ajaxRespond(true, 'success', 'LAPVIP xin được cám ơn những đánh giá vào nhận xét của bạn');

    }


    public function installment(Request $request){
        $product = Product::where('id', $request->id)->where('status', '>', 1)->select('id', 'title', 'alias', 'image')->first();
        return \Lib::ajaxRespond(true, 'success', ['url' => route('installment.scenarios', ['alias' => $product['alias'], '_token' => $request->_token,'index' => $request->index, 'id' => $request->id, 'filter_key' => (string)$request->filter_key, 'quan'=>$request->quan])]);
    }

    public function loadInstallmentScenariosByID(Request $request){
        $payment = InstallmentDetail::where('installment_scenarios_id', '=', $request->termID)->whereNull('installment_id')->first();
        return \Lib::ajaxRespond(true, 'success', $payment);
    }
    public function savePaymentByBankID(Request $request){
        $product = Product::getByPriceFilterKey($request->product,$request->filter,$request->quan + ( isset($current_quan) && $current_quan ? $current_quan : 0));
        $ins_bank = InstallmentBank::where('status', '>', 1)->where('title', $request->nameBank)->first();
        $ins = InstallmentDetail::where('installment_id', $ins_bank['id'])->first();
        $properties = \GuzzleHttp\json_decode($ins['properties']);
        foreach ($properties as $item){
            if ($request->payment == $item->payment_title){
                foreach ($item->month as $key => $item_month){
                    if($item_month->month == $request->month){
                        $conversion_fee = $ins_bank->surcharge + ($product->price * ($item_month->props[$key]->conversion_fee / 100)) + ($item_month->month * ($item_month->props[$key]->interest_rate / 100)) ;
                        $monthly_installments = ($product->price  + $conversion_fee) / $item_month->month;
                        $total_cost = $product->price + $conversion_fee;

                        if (!empty($request->filter)){
                            $filters = Filter::getWithCate(explode(',',$request->filter));
                            if(!empty($filters)) {
                                foreach($filters as $filter){
                                    $temp = [
                                        'filter_cate_title' => $filter->filter_cate->title,
                                        'filter_value' => $filter->title
                                    ];
                                    $opt['meta'][] = $temp;
                                }
                            }
                        }

                        $ins_s = new InstallmentSuccess();
                        $ins_s->_token = $request->_token;
                        $ins_s->name = $request->name;
                        $ins_s->buyer_sex = $request->buyer_sex;
                        $ins_s->date_of_birth = $request->dateofbirth;
                        $ins_s->cmtnd = $request->cmtnd;
                        $ins_s->phone = $request->phone;
                        $ins_s->product_id = $request->product;
                        $ins_s->filter_key = @$opt['meta'] ? json_encode($opt['meta']) : '';
                        $ins_s->quan = $request->quan;
                        $ins_s->type = $request->type;
                        $ins_s->month = $request->month;
                        $ins_s->money_paid_by_card = 0;
                        $ins_s->bank = $request->nameBank;
                        $ins_s->payment = $request->payment;
                        $ins_s->difference = $conversion_fee;
                        $ins_s->monthly_installments = $monthly_installments;
                        $ins_s->conversion_fee = $conversion_fee;
                        $ins_s->payment_upon_receipt = 0;
                        $ins_s->total_cost = $total_cost;
                        $ins_s->status = 0;
                        $ins_s->created = strtotime(now());

                        $ins_s->save();
                        return \Lib::ajaxRespond(true, 'success');
                    }
                }
            }

        }
    }
    public function loadPaymentByBankID(Request $request){
        $payment = InstallmentDetail::where('installment_id', '=', $request->idBank)->whereNull('installment_scenarios_id')->first();
        return \Lib::ajaxRespond(true, 'success', $payment);
    }
    public function searchProductCompare(Request $request){
        $product = Product::getByAlias($request->AliasP)->first();
        $cate_product = $product->category->id;
        $p_start = ($product->price + (($product->price / 100) * 10));
        $p_end = ($product->price - (($product->price / 100) * 10));
        $proChild = Product::getProductCompare($product->id, $cate_product, $p_start, $p_end, $request->data,3)->get()->toArray();
        $html = \View::make('FrontEnd::layouts.components.search_compare',['productChild' => $proChild, 'alias' => $request->AliasP] )->render();
        return \Lib::ajaxRespond(true,'success',$html);
    }

    public function searchProductInstallment(Request $request){
        $product = Product::getByAlias($request->alias)->first();
        $cate_product = $product->category->id;

        $data = Product::getProductsInstallment($cate_product, $product['id'],$request->data, 20);
        $html = \View::make('FrontEnd::layouts.components.search_product_installment',['data' => $data, 'index' => $request->index] )->render();
        return \Lib::ajaxRespond(true,'success',$html);
    }

    public function loadMoreAjax(Request $request){
        $page = $request->page;
        $pid = $request->pid;
        $sort_by = isset($request->sort_by) ? $request->sort_by : 3;
        $filter_ids = $request->filter_ids;
        $filter_ids = $filter_ids ? explode(',', $filter_ids) : '';
        $product['data'] = Product::getByCate($pid,'','',$filter_ids,'',$sort_by)->paginate(16, ['*'], 'page', $page)->appends(Input::except('page'));
        $product['count'] = $product['data']->lastPage() - $product['data']->currentPage();
        return \Lib::ajaxRespond(true, 'success', ['product' => $product]);


    }
    public function loadMoreFilterAjax(Request $request){
        $page = $request->page;
        $id = $request->id;
        $pid = $request->pid;
        $sort_by = isset($request->sort_by) ? $request->sort_by : 3;
        $filter_ids = $request->filter_ids;
        $filter_ids = $filter_ids ? explode(',', $filter_ids) : '';
        $product['data'] = Product::getByCate(isset($id) && !empty($id)? $id : $pid,'','',$filter_ids,'',$sort_by)->paginate(15, ['*'], 'page', $page)->appends(Input::except('page'));
        $product['count'] = $product['data']->lastPage() - $product['data']->currentPage();
        return \Lib::ajaxRespond(true, 'success', ['product' => $product]);


    }

    public function loadMoreSearchAjax(Request $request){
        $page = $request->page;
        $key = $request->key;
        $sort_by = isset($request->sort_by) ? $request->sort_by : 3;
        $arr_key = explode(' ', $request->key);
        $only_integers = array_filter($arr_key,'ctype_digit');
        $product['data'] = Product::getProductsByKey($key, $only_integers, $sort_by)->paginate(16, ['*'], 'page', $page)->appends(Input::except('page'));
        $product['count'] = $product['data']->lastPage() - $product['data']->currentPage();
        return \Lib::ajaxRespond(true, 'success', ['product' => $product]);


    }
    public function ajaxgetDataP(Request $request){
        $product = Product::where('id', $request->id)->select('id', 'title', 'title_sub', 'price', 'priceStrike', 'out_of_stock')->first();
        if (!empty($product)){
            $prd_have_sale = ProductDetail::where('product_id', $product['id'])->first();
            return \Lib::ajaxRespond(true, 'success', ['product' => $product, 'prd_have_sale' => $prd_have_sale['properties']]);
        }
    }
    public function nothing(){
        return "Nothing...";
    }
}
