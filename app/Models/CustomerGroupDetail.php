<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerGroupDetail extends Model
{
    protected $table = 'customer_group_detail';
    public $timestamps = false;

    public function product(){
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
