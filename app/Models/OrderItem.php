<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class OrderItem extends Model
{
    protected $table = 'order_items';
    public $timestamps = false;

    public function getImageUrl($size = 'original'){
        return \ImageURL::getImageUrl($this->img, Product::KEY, $size);
    }

    public function order() {
        return $this->hasOne("\App\Models\Order",'id','order_id');
    }

    public function product() {
        return $this->hasOne('\App\Models\Product','id','product_id');
    }

    public static function getImage($img,$size = 'original'){
        return \ImageURL::getImageUrl($img, Product::KEY, $size);
    }

    public static function returnOrderItemObjs($items,$order_id) {
        $arr = [];
        foreach($items as $itm) {
            $obj_bookingitem = new OrderItem();
            $obj_bookingitem->order_id = $order_id;
            $obj_bookingitem->product_id = $itm['id'];
            $obj_bookingitem->name = @$itm['name'] ?? @$itm['title'];
            $obj_bookingitem->price = $itm['price'];
            $obj_bookingitem->quantity = @$itm['quan'] ?? @$itm['quantity'];
            $obj_bookingitem->img = @$itm['opt']['img_or'] ?? @$itm['image'];
            $obj_bookingitem->note = @$itm['opt']['opt']['note'] ?? '';
            $obj_bookingitem->opts = @$itm['opt']['meta'] ? json_encode($itm['opt']['meta']) : '';
            $obj_bookingitem->filter_ids = @$itm['filter_key'];

            $arr[] = $obj_bookingitem;
        }
        return $arr;
    }

}