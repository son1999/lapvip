<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model{
    protected $table = 'warehouse';
    protected $fillable = ['id', 'title', 'province_id', 'status'];

    public static function getAll(){
        return self::where('status','>',-1)->get();
    }

    public function province(){
        return $this->hasOne(GeoProvince::class, 'id', 'province_id');
    }

    public static function getWarehouse(){
        $wery = \DB::table('warehouse')
            ->leftJoin('_geovnprovince','_geovnprovince.id', '=', 'warehouse.province_id')
            ->select('_geovnprovince.id', 'warehouse.location', 'warehouse.phone')
            ->where('warehouse.status', '>', 1)
            ->get()->toArray();
        return $wery;
    }

    public static function getWarehousePro(){
        return self::where('status', '>', 1)->select('id', 'title')->get();
    }
}
