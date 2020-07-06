<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Storage extends Model {

    protected $table = 'storage';
    protected $fillable = ['id', 'prd_price_id', 'warehouse_id', 'amount'];
    public $timestamps = false;
    public static function getByPriceIds($id = [])
    {
        $wery = self::whereIn('prd_price_id', $id);

        return $wery->get();
    }
    public function prd_price(){
        return $this->hasOne(ProductPrices::class, 'id', 'prd_price_id');
    }

    public function warehouse(){
        return $this->hasOne(Warehouse::class, 'id', 'warehouse_id');
    }

}