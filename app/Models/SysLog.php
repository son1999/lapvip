<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysLog extends Model
{
    //
    protected $table = '__logs';
    public $timestamps = false;

    public function getAction(){
        return \MyLog::do()->getAction($this->action);
    }

    public function getDevice(){
        return $this->device == 1 ? 'Mobile' : 'Web';
    }
}
