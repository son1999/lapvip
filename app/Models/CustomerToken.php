<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerToken extends Model
{
    //
    protected $table = 'customer_tokens';
    protected $tokenKey = '**#$$$$#has#all#@!!';
    protected $max_time = 86400;
    protected $primaryKey = 'customer_id';
    public $timestamps = false;
    public $incrementing = false;

    public function token(){
        return md5 ($this->email .' - '.$this->created_at.' - '.$this->type.' '.$this->tokenKey);
    }

    public function newToken($type = 1){
        $this->type = $type;
        $this->created_at = time();
        $this->token = $this->token();
        $this->save();
    }

    public function verify($token, &$err = '', $type = 1){
        if($this->created_at + $this->max_time < time()){
            $err = 'time_out';
        }else if($token != $this->token()){
            $err = 'fail';
        }else{
            return true;
        }
        return false;
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }
}
