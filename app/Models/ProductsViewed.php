<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class ProductsViewed extends Model
{
    //
    protected $table = 'product_viewed';
    public $timestamps = false;

    public function viewed(){
        return $this->hasMany(Product::class, 'id', 'product_id');
    }
    
    public static function getProductViewedById($id, $cus_id) {
        return self::where([['product_id', $id], ['cus_id', $cus_id]])->first();
    }
    
    public static function createNewProductViewed($id, $cus_id) {
        return self::insert([
            'cus_id' => $cus_id,
            'product_id' => $id,
            'created' => time()
        ]);
    }
    
    public static function updateProductViewed($id, $cus_id) {
        return self::where([['product_id', $id], ['cus_id', $cus_id]])
        ->update([
            'created' => time(),
        ]);
    }
}

