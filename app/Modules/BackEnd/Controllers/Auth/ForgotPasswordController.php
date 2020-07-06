<?php

namespace App\Modules\BackEnd\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showLinkRequestForm()
    {
        return view('BackEnd::auth.passwords.email',['site_title' => 'Quên mật khẩu']);
    }

    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        $user = User::whereEmail($request->email)->first();
        if($user) {
            $newData = [
                'email' => $user->email,
                'token' => str_random(64),
                'created_at' => time()
            ];
            $table = config('auth.passwords.users.table');
            \DB::table($table)->whereEmail($user->email)->delete();
            \DB::table($table)->insert($newData);
            event('user.password', [$newData, $user]);

            return redirect()->back()->with('status', 'Email lấy lại mật khẩu đã được gửi đi, vui lòng kiểm tra Inbox/Spam/Bulk và làm theo hướng dẫn');
        }
        return back()->withInput()->withErrors(
            ['email' => 'Không tìm thấy thông tin']
        );
    }
}
