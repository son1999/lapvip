<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ProductDetail extends Model
{
    //
    protected $table = 'product_detail';
    public $timestamps = false;

    public $fillable = ['properties', 'promote_props'];

    public function product()
    {
        return $this->hasOne('App\Models\Product','id','product_id');
    }



}
