<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductRelate extends Model
{
    //
    protected $table = 'product_relates';
    public $timestamps = false;

    public static function getByPrdId($id)
    {
        $wery = self::where('product_id',$id);
        return $wery->get();
    }

    public function product(){
        return $this->hasOne(Product::class, 'id', 'id_relate')->select('id', 'price', 'alias');
    }
}
