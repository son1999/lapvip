<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryCat extends Model
{
    //
    protected $table = 'gallery_cats';
    public $timestamps = false;

    public static function getCategories($json = false, $array = true){
        $data = self::orderByRaw('safe_title, created DESC')->get();
        if($array || $json) {
            $data = $data->toArray();
        }
        return $json ? json_encode($data) : $data;
    }

    public static function updateQuantity($id = 0){
        if($id > 0) {
            $count = Gallery::where('cat_id', $id)->count();
            return self::where('id', $id)->update(['total' => $count]);
        }
        return false;
    }
}
