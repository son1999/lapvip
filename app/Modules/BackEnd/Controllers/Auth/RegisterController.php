<?php

namespace App\Modules\BackEnd\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin/register/success';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('BackEnd::auth.register', [
            'site_title' => 'Đăng kí'
        ]);
    }

    public function success()
    {
        return view('BackEnd::auth.register_success', [
            'site_title' => 'Đăng kí thành công'
        ]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data,
            [
                'user_name' => 'required|min:3|max:20|regex:#^[_a-zA-Z][0-9_a-zA-Z]*$#|unique:users,user_name',
                'email' => 'required|email|max:255|unique:users,email',
                'password' => 'required|min:6|confirmed',
            ],
            [
                'user_name.unique' => 'Tên đăng nhập đã được sử dụng',
                'user_name.required' => 'Tên đăng nhập là bắt buộc',
                'user_name.min' => 'Tên đăng nhập phải từ 3 kí tự trở lên',
                'user_name.max' => 'Tên đăng nhập chỉ chứa tối đa 20 kí tự',
                'user_name.regex' => 'Tên đăng nhập bắt đầu bằng chữ cái, và chỉ chấp nhận chữ, số, kí tự "_"',
                'email.required' => 'Chưa nhập email',
                'email.max' => 'Email quá dài, tối đa 255 kí tự',
                'email.email' => 'Email không hợp lệ',
                'email.unique' => 'Email đã được sử dụng',
                'password.required' => 'Chưa nhập mật khẩu',
                'password.min' => 'Mật khẩu phải có 6 kí tự trở lên',
                'password.confirmed' => 'Mật khẩu chưa khớp nhau'
            ]
        );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'user_name' => $data['user_name'],
            'email' => $data['email'],
            'reg_ip' => \Request::ip(),
            'active' => 0,
            'status' => 1,
            'created' => time(),
            'password' => bcrypt($data['password']),
        ]);
    }
}
