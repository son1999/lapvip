<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeoProvince extends Model
{
    //
    protected $table = '_geovnprovince';
    public $timestamps = false;

    public static function getAll() {
        return self::orderBy('safe_title')->get();
    }

    public static function getListProvinces(){
        $cities =  self::orderBy('safe_title')->get()->keyBy('id')->all();
        $data = [];
        foreach($cities as $k=>$r){
            $data[$r->id] = $r->Name_VI;
        }
        return $data;
    }

    public function warehouses(){
        return $this->hasMany(Warehouse::class);
    }

    public function districts(){
        return $this->hasMany(GeoDistrict::class, 'Province_ID');
    }

    public static function getProvince(){
        $wery = \DB::table('_geovnprovince')
            ->leftJoin('warehouse','warehouse.province_id', '=', '_geovnprovince.id')
            ->select('_geovnprovince.id', '_geovnprovince.Name_VI')
            ->where('warehouse.status', '>', 1)
            ->get()->toArray();
        return $wery;
    }

}



