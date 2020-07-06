<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

define('USER_REMOVED', -1);
define('USER_BANNED', 0);
define('USER_PENDING', 1);
define('USER_ACTIVED', 2);
define('USER_NOT_LOGIN', 3);
define('USER_OFFLINE', 4);
define('USER_ONLINE', 5);

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    public $timestamps = false;
    protected $statusCode = -1000;
    const CheckOnlineTime = 1800;
    const KEY = 'user';
    const StatusText = [
        USER_REMOVED => 'Đã xóa',
        USER_BANNED => 'Bị khóa',
        USER_PENDING => 'Chưa kích hoạt',
        USER_ACTIVED => 'Đã kích hoạt',
        USER_NOT_LOGIN => 'Chưa hoạt động',
        USER_OFFLINE => 'Offline',
        USER_ONLINE => 'Online'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'user_name', 'email', 'phone', 'fullname', 'reg_ip', 'last_login_ip', 'last_login', 'created', 'active', 'status', 'last_active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isRoot(){
        return $this->id == 1;
    }

    public function isAdmin(){
        $check = $this->roles->where('id', 1)->toArray();
        return !empty($check);
    }

    public function isOnline(){
        if($this->last_logout > 0){
            return false;
        }
        $check_online_time = time() - self::CheckOnlineTime;
        return $this->last_active >= $check_online_time;
    }

    public function getStatus(){
        if($this->statusCode == -1000) {
            $this->statusCode = USER_PENDING;
            switch ($this->status) {
                case -1:
                    $this->statusCode = USER_REMOVED;
                    break;
                case 0:
                    $this->statusCode = USER_BANNED;
                    break;
                default:
                    if ($this->active > 0) {
                        if ($this->last_login == 0) {
                            $this->statusCode = USER_NOT_LOGIN;
                        } else {
                            $this->statusCode = $this->isOnline() ? USER_ONLINE : USER_OFFLINE;
                        }
                    }
            }
        }
        return $this->statusCode;
    }

    public static function getStatusOpt(){
        return self::StatusText;
    }

    public function getStatusText(){
        return self::StatusText[$this->getStatus()];
    }

    public function getStatusClass(){
        switch ($this->getStatus()){
            case USER_REMOVED: case USER_BANNED:
                return 'danger';
            case USER_PENDING: case USER_NOT_LOGIN:
                return 'warning';
            case USER_ACTIVED: case USER_ONLINE:
                return 'success';
            case USER_OFFLINE:
                return 'secondary';
        }
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'uid', 'rid');
    }

    public function hasAccess($permissions)
    {
        if($this->isRoot() || $this->isAdmin()){
            return true;
        }
        foreach ($permissions as $k => $v) {
            $permissions[$k] = str_replace('App\Models\Role::', '', $v);
        }
        foreach ($this->roles as $role) {
            if($role->hasAccess($permissions)) {
                return true;
            }
        }
        return false;
    }

    public static function forceLogout($uid){
        return self::where('id', $uid)->update(['logout' => 1]);
    }

    public function biggerThanYou($uid){
        if(!$this->isRoot()) {
            $you = self::find($uid);
            if ($you) {
                if ($this->roles->isEmpty()) {
                    return false;
                } elseif (!$you->roles->isEmpty()) {
                    $myMin = -1;
                    foreach ($this->roles as $role) {
                        if ($myMin == -1 || $myMin > $role->rank) {
                            $myMin = $role->rank;
                        }
                    }
                    $yourMin = -1;
                    foreach ($you->roles as $role) {
                        if ($yourMin == -1 || $yourMin > $role->rank) {
                            $yourMin = $role->rank;
                        }
                    }
                    return $myMin < $yourMin;
                }
            }
        }
        return true;
    }

    public function checkMyRank($rank){
        if($this->isRoot()) {
            return true;
        }
        if (!$this->roles->isEmpty()) {
            foreach ($this->roles as $role) {
                if ($rank > $role->rank) {
                    return true;
                }
            }
        }
        return false;
    }

    public function getImageAvatar($size = 'original'){
        return \ImageURL::getImageUrl($this->image, 'user', $size);
    }


}
