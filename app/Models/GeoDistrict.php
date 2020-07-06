<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeoDistrict extends Model
{
    //
    protected $table = '_geovndistrict';
    public $timestamps = false;

    public function getType(){
        $names = [
            0 => __('site.quan'),
            1 => __('site.huyen'),
            2 => __('site.thanhpho'),
            3 => __('site.thixa'),
            4 => ''
        ];
        return $names[$this->type];
    }

    public static function getListDistrictsByCity($city){
        return self::where ('Province_ID', $city)->orderBy('safe_title')->get()->keyBy('id')->all();
    }

    public static function getAddress($id){
        $data = DB::table('customer_addresses address,_geovndistrict district,_geovnprovince province,_geovnward ward')
            ->select('address.id, address.fullname, address.phone, address.email, province.Name_VI, district.Name_VI, ward.Name_VI, address.address')
            ->where('address.district_id','district.id')
            ->where('address.province_id','province.id')
            ->where('address.ward_id','ward.id')
            ->where('address.id',$id)
            ->get();
        return $data;
    }

    public function province(){
        return $this->belongsTo(GeoProvince::class, 'Province_ID', 'id', '');
    }
}