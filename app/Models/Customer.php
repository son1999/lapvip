<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

define('CUSTOMER_REMOVED', -1);
define('CUSTOMER_BANNED', 0);
define('CUSTOMER_PENDING', 1);
define('CUSTOMER_ACTIVED_PHONE', 2);
define('CUSTOMER_ACTIVED_EMAIL', 3);
define('CUSTOMER_ACTIVED_ALL', 4);
define('CUSTOMER_NOT_LOGIN', 5);
define('CUSTOMER_OFFLINE', 6);
define('CUSTOMER_ONLINE', 7);

class Customer extends Authenticatable {

    use Notifiable;

    protected $table = 'customers';
    protected $guard = 'customer';
    public $timestamps = false;
    protected $checkOnlineTime = 1800;
    protected $statusCode = -1000;
    protected $statusText = [
        CUSTOMER_REMOVED => 'Đã xóa',
        CUSTOMER_BANNED => 'Bị khóa',
        CUSTOMER_PENDING => 'Chưa kích hoạt',
        CUSTOMER_NOT_LOGIN => 'Chưa hoạt động',
        CUSTOMER_ACTIVED_EMAIL => 'Đã kích hoạt email',
        CUSTOMER_ACTIVED_PHONE => 'Đã kích hoạt điện thoại',
        CUSTOMER_ACTIVED_ALL => 'Đã kích hoạt',
        CUSTOMER_OFFLINE => 'Offline',
        CUSTOMER_ONLINE => 'Online'
    ];

    const CUSTOMER_LAYER = 'customer_layer';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'user_name', 'email', 'phone', 'password', 'fullname',
        'reg_ip', 'last_login_ip', 'last_login', 'last_active',
        'created', 'active', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function token() {
        return $this->hasOne('App\Models\CustomerToken');
    }
    public function groups()
    {
        return $this->belongsToMany(CustomerGroup::class, 'customer_group_connect', 'customer_id', 'customer_group_id')->withPivot('created');
    }

    public function isOnline() {
        $check_online_time = time() - $this->checkOnlineTime;
        return $this->last_active >= $check_online_time;
    }

    public static function getDiscount($customer)
    {
        if (!empty($customer)){
            $customer_gp = Customer::with('groups' )->where('id', $customer->id)->first();
            $percent = $customer_gp['groups']->max('percent');
            return $percent;
        }
        else{
            return 0;
        }


    }

    public function getStatus() {
        if ($this->statusCode == -1000) {
            $this->statusCode = CUSTOMER_PENDING;
            switch ($this->status) {
                case -1:
                    $this->statusCode = CUSTOMER_REMOVED;
                    break;
                case 0:
                    $this->statusCode = CUSTOMER_BANNED;
                    break;
                default:
                    switch ($this->active) {
                        case 1:
                            $this->statusCode = CUSTOMER_ACTIVED_EMAIL;
                            break;
                        case 2:
                            $this->statusCode = CUSTOMER_ACTIVED_PHONE;
                            break;
                        case 3:
                            $this->statusCode = CUSTOMER_ACTIVED_ALL;
                            break;
                    }
            }
        }
        return $this->statusCode;
    }

    public function getStatusText() {
        return $this->statusText[$this->getStatus()];
    }

    public function getStatusClass() {
        switch ($this->getStatus()) {
            case CUSTOMER_REMOVED: case CUSTOMER_BANNED:
                return 'danger';
            case CUSTOMER_PENDING: case CUSTOMER_NOT_LOGIN:
                return 'warning';
            case CUSTOMER_ACTIVED_ALL: case CUSTOMER_ACTIVED_EMAIL: case CUSTOMER_ACTIVED_PHONE: case CUSTOMER_ONLINE:
                return 'success';
            case CUSTOMER_OFFLINE:
                return 'secondary';
        }
    }

    public static function createOne($request) {
        if (!empty($request['fullname'])) {
            $fullname = $request['fullname'];
        } else {
            $fullname = explode(" ", $request['name']);
            $fullname = $fullname[0];
        }

        //tao customer moi
        $customer = new Customer;
        $customer->user_name = !empty($request['user_name']) ? strtolower($request['user_name']) : $request['name'];
        $customer->fullname = $fullname;
        $customer->email = $request['email'];
        $customer->password = bcrypt($request['password']);
        $customer->province = $request->provinces;
        $customer->district = $request->districts;
        $customer->reg_ip = request()->ip();
        $customer->active = !empty($request['active']) ? $request['active'] : 0;
        $customer->status = 1;
        $customer->created = time();
        $customer->save();
        //add to group mac dinh
        $customer_group_id = !empty($request['customer_group_id']) ? strtolower($request['customer_group_id']) : CustomerGroup::GROUP_DEFAULT;
        $customer->addToGroup($customer_group_id);

        if (!empty($customer->email)) {
            CustomerToken::where('email' , '=',  $customer->email)->delete();
            //tao token de active
            $customerToken = new CustomerToken();
            $customerToken->email = $customer->email;
            $customerToken->created_at = time();
            $customerToken->customer_id = $customer->id;
            $customerToken->type = 1;
            $customerToken->token = $customerToken->token();
            $customerToken->save();

            //them vao subscribe
//            $subscriber = Subscriber::where('email', $customer->email)->first();
//            if (empty($subscriber)) {
//                $subscriber = new Subscriber();
//                $subscriber->email = $customer->email;
//                $subscriber->created = time();
//            }
//            $subscriber->customer_id = $customer->id;
//            $subscriber->save();


        }
        return $customer;
    }

    public function getImageUrl($size = 'original') {
        return \ImageURL::getImageUrl($this->avatar, 'avatar', $size);
    }
    public function comments(){
        return $this->hasMany(Comment::class);
    }
    public function getActiveGroupWow(){
        $groups = $this->groups;
        $corp = false;

        foreach ($groups as $g) {
            if (!empty($g)) {
                if (empty($corp) && $g->isCorp()) {
                    $corp = $g;
                }
            }
        }
        return !empty($corp) ? $corp : CustomerGroup::getDefGroup();
    }

    public function checkActiveGroup()
    {
        session()->put(self::CUSTOMER_LAYER, false);
        $groups = $this->groups;
        $corp = false;
        foreach ($groups as $g) {
            if (!empty($g)) {

                if ($g->isSale() && $this->isActiveSale()) {
                    session()->put(self::CUSTOMER_LAYER, $g);
                    return true;
                }
                if (empty($corp) && $g->isCorp()) {
                    $corp = $g;
                }
            }
        }
        session()->put(self::CUSTOMER_LAYER, !empty($corp) ? $corp : CustomerGroup::getDefGroup());
        return true;
    }

    public function isLayerEndUser()
    {
        $group = session()->get(self::CUSTOMER_LAYER, false);
        return !empty($group) && $group->isEndUser();
    }

    public function addToGroup($groupId)
    {

        DB::transaction(function () use ($groupId) {
            if (is_array($groupId)){
                $this->groups()->sync($groupId);
            }else{
                $this->groups()->attach($groupId);
            }
        });
    }

    public function removeToGroup($groupId)
    {
        DB::transaction(function () use ($groupId){
            $this->groups()->detach($groupId);
        });
	}
    public function questions(){
        return $this->hasMany(Question::class);
    }

    public static function changePass($request){
        $user = \Auth::guard('customer')->user();
        $user->password = bcrypt($request->newPassword);
        $user->save();

        return \Lib::ajaxRespond(true, 'Đổi mật khẩu thành công!!! Yêu cầu đăng nhập lại', ['url' => route('logout')]);
    }
}
