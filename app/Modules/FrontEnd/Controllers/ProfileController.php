<?php

namespace App\Modules\FrontEnd\Controllers;

use Illuminate\Http\Request;
use App\Models\GeoDistrict;
use App\Models\GeoProvince;
use App\Models\ProductsViewed;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Validator;
class ProfileController extends Controller
{   //
    public function __construct(){
        $this->middleware('auth:customer');
    }

    
    public function index(){
        return view('FrontEnd::pages.profile.index', [
            'site_title' => 'Trang cá nhân',
            'data' => \Auth::guard('customer')->user(),
            'profile_menu' => $this->profileMenu('profile'),
            'pro' => GeoProvince::all(),
        ]);
    }

    public function orders(){
        
        $orders = Order::with('items')->where('customer_id', \Auth::guard('customer')->user()->id)->paginate(5);
        return view('FrontEnd::pages.profile.order', [
            'site_title' => 'Trang cá nhân',
            'data' => \Auth::guard('customer')->user(),
            'profile_menu' => $this->profileMenu('orders'),
            'pro' => GeoProvince::all(),
            'orders' => $orders,
        ]);
    }

    public function orderDetail($id){

        $customer = \Auth::guard('customer')->user();
        // $notifi = Notification::getNoti($customer->id);
        $details = Order::with('items')->where([['customer_id', \Auth::guard('customer')->user()->id], ['code', $id]])->get()->toArray();
        return view('FrontEnd::pages.profile.order-detail',[
            'site_title' => 'Chi tiết đơn hàng',
            'profile_menu' => $this->profileMenu('orders'),
            'details' => $details,
        ]);
    }

    public function viewed(){
        $cus_id = \Auth::guard('customer')->user()->id;
        $viewd = ProductsViewed::with('viewed')->where('cus_id', $cus_id)->orderByRaw('created DESC, id DESC')->paginate(12,['*'],'viewed');
        
        
        return view('FrontEnd::pages.profile.viewed',[
            'site_title' => 'Sản phẩm đã xem',
            'prd_history' => $viewd,
            'profile_menu' => $this->profileMenu('viewed'),
        ]);
    }

    public function update(Request $request){
        $customer = \Auth::guard('customer')->user();
            $valid = [
                'fullname' => 'required',
                'email' => 'required|email',
                'province_profile' => 'required',
                'district_profile' => 'required',
                'phone' => 'required|numeric|unique:customers,phone,'.$customer->id,
                'address' => 'required'
            ];
            $messages = [
                    'fullname.required' => 'Chưa nhập họ tên',
                    'email.required' => 'Chưa nhập email',
                    'email.email' => 'Email ko được định dạng',
//                    'email.unique' => 'Email đã được sử dụng',
                    'province_profile.required' => 'Vui lòng chọn thành phố',
                    'district_profile.required' => 'Vui lòng chọn Quận/Huyện',
                    'phone.required' => 'Chưa nhập số điện thoại',
                    'phone.numeric' => 'Số điện thoại chỉ chấp nhận chữ số',
                    'phone.unique' => 'Số điện thoại đã được sử dụng',
                    'address.required' => 'Bạn không được để trống trường địa chỉ',

//                    'address.regex' => 'Địa chỉ bạn nhập vào không được chứa ký tự đặc biệt'
                ];


            $validator = Validator::make($request->all(), $valid, $messages);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            else{

                $customer->fullname = $request->fullname;
                $customer->phone = $request->phone;
                $customer->email = $request->email;
                $customer->gender = $request->gender;
                $customer->province = $request->province_profile;
                $customer->district = $request->district_profile;
                $customer->address = $request->address;

                $customer->save();

                return redirect()->route('profile')->with('success','Thành công') ;
            }

        // $customer->fullname = $request->fullname;
        // $customer->phone = $request->phone;
        // $customer->gender = $request->gender;

        // if($request->new_password != '') {
        //     $customer->password = bcrypt($request->new_password);
        // }

        // //process image
        // if ($request->hasFile('image')) {
        //     $image = $request->file('image');
        //     if ($image->isValid()) {
        //         $fname = \ImageURL::makeFileName($request->fullname, $image->getClientOriginalExtension());
        //         $image = \ImageURL::upload($image, $fname, 'avatar');
        //         if($image){
        //             $customer->avatar = $fname;
        //         }else{
        //             redirect()->back()->withInput()->withErrors(['image' => 'Upload ảnh lên server thất bại!']);
        //         }
        //     }else{
        //         redirect()->back()->withInput()->withErrors(['image' => 'Upload ảnh thất bại!']);
        //     }
        // }
        // $customer->save();
        // return redirect()->route('profile')->with('status', 'Cập nhật thông tin thành công');
    }

    public function profileMenu($active = ''){
        return [
            [
                'title' => 'Hồ sơ của tôi',
                'link'  => route('profile'),
                'icon'  => 'fa-address-card-o',
                'active'=> $active == 'profile'
            ],
            [
                'title' => 'Các sản phẩm đã xem',
                'link'  => route('viewed'),
                'notice' => 1,
                'icon'  => 'fa-bell',
                'active'=> $active == 'viewed'
            ],
            [
                'title' => 'Quản lý đơn hàng',
                'link'  => route('orders'),
                'icon'  => 'fa-address-card-o',
                'active'=> $active == 'orders'
            ],
            [
                'title' => 'Đăng xuất',
                'link'  => route('logout'),
                'icon'  => 'iUser',
                'active'=> false
            ]
        ];
    }
}
