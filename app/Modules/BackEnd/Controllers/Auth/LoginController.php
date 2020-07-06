<?php

namespace App\Modules\BackEnd\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('BackEnd::auth.login', [
            'site_title' => 'Đăng nhập'
        ]);
    }

    public function username()
    {
        return "user_name";
    }

    public function logout(Request $request)
    {
        \MyLog::do()->add('info-logout');

        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('admin/login');
    }
}
