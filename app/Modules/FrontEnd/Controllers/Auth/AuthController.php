<?php

namespace App\Modules\FrontEnd\Controllers\Auth;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerToken;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{   //
    public function __construct(){

    }

    public function showRegSuccess(Request $request){
        return view('FrontEnd::auth.register_success', [
            'site_title' => 'Đăng kí thành công',
            'email' => $request->email
        ]);
    }

    public function showPasswordRequestForm()
    {
        return view('FrontEnd::auth.password', [
            'site_title' => trans('olala.laylaimatkhau'),
        ]);
    }

    public function showPasswordResetForm(Request $request)
    {
        $token = CustomerToken::whereToken($request->token)->first();

        return view('FrontEnd::auth.password_reset', [
            'site_title' => 'Tạo mật khẩu mới',
            'token' => $token
        ]);
    }

    public function showLoginForm()
    {
        if(\Auth::guard('customer')->check()){
            return redirect()->route('home');
        }

        return view('FrontEnd::auth.login', [
            'site_title' => 'Đăng nhập'
        ]);
    }

    public function sendResetLinkEmail(Request $request){
        $this->validate($request,
            [
                'email' => 'required|email|exists:customers,email'
            ],
            [
                'email.required' => 'Chưa nhập email',
                'email.email' => 'Email không hợp lệ',
                'email.exists' => 'Email không hợp lệ'
            ]
        );
        $customer = Customer::whereEmail($request->email)->first();
        if(empty($customer->token)){
            $customerToken = new CustomerToken();
            $customerToken->email = $customer->email;
            $customerToken->created_at = time();
            $customerToken->customer_id = $customer->id;
            $customerToken->type = 2;
            $customerToken->token = $customerToken->token();
            $customerToken->save();
        }else{
            $customer->token->newToken(2);
        }
        event('customer.password', $customer->id);

        return redirect()->route('home')->with('status', 'Email lấy lại mật khẩu đã được gửi đi, vui lòng kiểm tra Inbox/Spam/Bulk và làm theo hướng dẫn');
    }

    public function reset(Request $request){
        $this->validate($request,
            [
                'email' => 'required|email|exists:customers,email',
                'password' => 'required|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!\.$#%@]).*$/',
                'password_confirm' => 'required|same:password'
            ],
            [
                'email.required' => 'Chưa nhập email',
                'email.email' => 'Email không hợp lệ',
                'email.exists' => 'Email không hợp lệ',
                'password.required' => 'Chưa nhập Mật khẩu',
                'password.min' => 'Mật khẩu phải có từ 8 kí tự trở lên',
                'password.regex' => 'Mật khẩu phải bao gồm chữ, số và kí tự đặc biệt (!, $, #, %, @)',
                'password_confirm.required' => 'Chưa nhập lại mật khẩu',
                'password_confirm.same' => 'Mật khẩu không khớp',
            ]
        );

        $err = ''; $msg = '';
        if(\Lib::isWeakPassword($request->password)){
            $err = 'password';
            $msg = 'Mật khẩu quá yếu';
        }
        if(empty($err)) {
            $customer = Customer::whereEmail($request->email)->first();
            if ($customer->token && $customer->token->verify($request->token, $err, 2)) {

                $customer->password = bcrypt($request->password);
                $customer->save();

                //dang nhap luon
                if (\Auth::guard('customer')->attempt(['email' => $request->email, 'password' => $request->password])) {
                    $request->session()->regenerate();
                    return redirect()->route('home');
                }
            }
            $msg = 'Yêu cầu của bạn không hợp lệ';
            if ($err == '') {
                $err = 'not_valid';
            } elseif ($err == 'time_out') {
                $msg = 'Yêu cầu của bạn đã quá hạn, vui lòng thực hiện lại';
            }
        }
        return redirect()->back()->withInput()->withErrors([$err => $msg]);
    }

    public function regVerify(Request $request){
        $email = $request->email;
        $customer = Customer::whereEmail($email)->first();
        $err = '';
        $show_resend = false;
        if($customer) {
            if ($customer->status < 1) {
                $err = 'Tài khoản đã bị khóa hoặc bị xóa';
            } elseif ($customer->active > 0) {
                $err = 'Tài khoản đã được kích hoạt';

                //thoát luôn ra trang chủ
                return redirect()->route('home');
            }
        }else{
            $err = 'Tài khoản không hợp lệ';
        }

        if($err == ''){
            $storeToken = CustomerToken::whereEmail($email)->first();
            if($storeToken->verify($request->token, $err)){
                //active tài khoản
                $customer->active = 1;
                $customer->save();

                //xoa bo token
                $customer->token->delete();
            }else{
                $err = $err == 'fail' ? 'Mã xác thực không đúng' : 'Đã hết hạn xác thực, vui lòng thực hiện lại';
                $show_resend = true;
            }
        }

        return view('FrontEnd::auth.active', [
            'site_title' => 'Xác thực tài khoản',
            'err' => $err,
            'customer' => $customer,
            'show_resend' => $show_resend,
        ]);
    }

    public function login(Request $request)
    {
        if(\Auth::guard('customer')->check()){
            return redirect()->route('home');
        }

        $this->validate($request,
            [
                'email' => 'required|email|exists:customers,email',
                'password' => 'required'
            ],
            [
                'email.required' => 'Chưa nhập email',
                'email.email' => 'Email không hợp lệ',
                'email.exists' => 'Email không hợp lệ',
                'password.required' => 'Chưa nhập Mật khẩu',
            ]
        );

        $customer = Customer::where('email', $request->email)->first();
        if ($customer->active > 0 && $customer->status > 0) {
            if (\Auth::guard('customer')->attempt(['email' => $request->email, 'password' => $request->password])) {
                $request->session()->regenerate();
                return redirect()->route('home');
            }
            $msg = ['password' => 'Nhập sai mật khẩu'];
        } else {
            if ($customer->active <= 0) {
                $msg = ['email' => 'Tài khoản chưa được kích hoạt'];
            } elseif ($customer->status <= 0) {
                $msg = ['email' => 'Tài khoản đã bị vô hiệu, không thể đăng nhập'];
            }
        }
        return redirect('login')
            ->withErrors($msg)
            ->withInput();
    }

    public function logout(Request $request)
    {
        \Auth::guard('customer')->logout();
        $request->session()->flush();
        $request->session()->regenerate();

        return redirect()->guest(route( 'home' ));
    }
}
