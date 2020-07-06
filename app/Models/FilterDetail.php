<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FilterDetail extends Model
{
    //
    protected $table = 'filter_details';
    public $timestamps = false;

    public static function getFilters($object_id){
        return self::where('object_id', '=', $object_id)->get();
    }

    public function filter() {
        return $this->hasOne('App\Models\Filter','id','filter_id');
    }

    public function product() {
        return $this->hasMany('App\Models\Product','id','object_id')->where('type','product');
    }
}
