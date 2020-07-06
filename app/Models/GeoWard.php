<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeoWard extends Model
{
    //
    protected $table = '_geovnward';
    public $timestamps = false;

    public function getType(){
        $names = [
            0 => __('site.phuong'),
            1 => __('site.xa'),
            2 => __('site.thitran'),
            3 => '',
        ];
        return $names[$this->type];
    }

    public static function getListwards($district){
        return self::where ('District_ID', $district)->orderBy('safe_title')->get()->keyBy('id')->all();
    }

}