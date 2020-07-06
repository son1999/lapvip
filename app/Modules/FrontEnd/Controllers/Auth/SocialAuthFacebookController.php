<?php
namespace App\Modules\FrontEnd\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Contracts\User as ProviderUser;

use App\Models\Customer;
use App\Models\SocialFacebookAccount;

class SocialAuthFacebookController extends Controller
{
    /**
     * Create a redirect method to facebook api.
     *
     * @return void
     */
    public function redirect()
    {
        return \Socialite::driver('facebook')->redirect();
    }

    /**
     * Return a callback method from facebook api.
     *
     * @return callback URL from facebook
     */
    public function callback()
    {
        $fb_user = \Socialite::driver('facebook')->user();
        $customer = $this->createOrGetUser($fb_user);
        if($customer) {
            if ($customer->status < 0) {
                return redirect()->route('login')->withErrors(['fb_login_fail' => 'Tài khoản kết nối đang tạm khóa hoặc bị vô hiệu hóa']);
            }
            \Auth::guard('customer')->login($customer, true);
            return redirect()->route('home');
        }
        return redirect()->route('login')->withErrors(['fb_login_fail' => 'Bạn chưa chia sẻ Email trên Facebook']);
    }

    protected function createOrGetUser(ProviderUser $providerUser)
    {
        $account = SocialFacebookAccount::whereProvider('facebook')
            ->whereProviderUserId($providerUser->getId())
            ->first();

        if ($account) {
            return $account->user;
        }

        $account = new SocialFacebookAccount([
            'provider_user_id' => $providerUser->getId(),
            'provider' => 'facebook'
        ]);
        $email = $providerUser->getEmail();
        if(!empty($email)) {
            $customer = Customer::whereEmail($email)->first();
            if (!$customer) {
                $customer = Customer::createOne([
                    'email' => $email,
                    'fullname' => $providerUser->getName(),
                    'password' => md5(rand(1, 10000) . ' okolala'),
                    'active' => 1,
                ]);
            } else {
                if ($customer->status > 0 && $customer->active <= 0) {
                    $customer->active = 1;
                    $customer->fullname = $providerUser->getName();
                    $customer->reg_ip = \Request::ip();
                    $customer->created = time();
                }
                SocialFacebookAccount::whereProvider('facebook')
                    ->where('user_id', '=', $customer->id)
                    ->delete();
            }
            $account->user()->associate($customer);
            $account->save();

            return $customer;
        }
        return false;
    }
}