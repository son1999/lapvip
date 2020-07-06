<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    //
    protected $table = 'user_roles';
    public $timestamps = false;

    protected $fillable = ['uid', 'rid'];

    public function role(){
        return $this->hasOne('App\Models\Role', 'id', 'rid');
    }
}
