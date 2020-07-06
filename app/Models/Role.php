<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //
    protected $table = 'roles';
    public $timestamps = false;

    protected static $permission = [];
    public static $defRoles = [
        'view' => 'Xem',
        'add' => 'Thêm mới',
        'edit' => 'Sửa',
        'delete' => 'Xóa'
    ];

    public static function getPermOfUserByKey($key, $user = false){
        $permissions = self::getPermissions();
        $ret = false;
        if(isset($permissions[$key])){
            $permissions = $permissions[$key];
            if(!empty($permissions['perm'])){
                $ret = [];
                if(empty($user)){
                    $user = \Auth::user();
                }
                foreach ($permissions['perm'] as $k => $v){
                    $ret[$k] = $user->can($key.'-'.$k, $key.'-'.$k);
                }
            }
        }
        return $ret;
    }

    public static function getPermissions(){
        if(empty(self::$permission)) {
            self::$permission = config('permission');
            self::$permission = self::$permission['backend'];
        }
        $permissions = [];
        foreach (self::$permission as $k => $routes){
            $perm = !empty($routes['perm']) ? $routes['perm'] : self::$defRoles;
            if(!empty($routes['perm_extra'])){
                $perm = array_merge($perm, $routes['perm_extra']);
            }
            $permissions[$k] = [
                'title' => $routes['title'],
                'perm' => $perm
            ];
        }
        //them quyen dac biet neu co
        /*$permissions['system'] = [
            'title' => 'Cấu hình hệ thống',
            'perm' => [
                'change' => 'Thay đổi cấu hình'
            ]
        ];*/
        return $permissions;
    }

    public function hasAccess($permissions)
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    private function hasPermission($permission)
    {
        $permission = explode('-', $permission);
        if(count($permission) <= 1){
            return false;
        }
        if(is_string($this->permit)){
            $this->permit = json_decode($this->permit, true);
        }
        return isset($this->permit[$permission[0]]) && in_array($permission[1], $this->permit[$permission[0]]);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles', 'rid', 'uid');
    }
}
