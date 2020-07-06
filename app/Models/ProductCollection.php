<?php


namespace App\Models;

use App\Models\Collection;
use Illuminate\Database\Eloquent\Model;

class ProductCollection extends Model {


    protected $table = 'product_collection';
    public $timestamps = false;
    
    public static function addCollections($collections, $id){
        //lay thong tin collection
//        foreach ($collections as $k => $v){
//            $collections[$k] = str_slug($v);
//        }
        $collections = Collection::select('id')
            ->where('status', '>', 1)
            ->whereIn('id', $collections)
            ->get()->toArray();
        if(!empty($collections)) {
            $insertData = [];
            foreach ($collections as $item) {
                $insertData[] = [
                    'product_id' => $id,
                    'collection_id' => $item['id'],
                ];
            }
            if (!empty($insertData)) {
                //xoa het tag cu
                self::where('product_id', $id)
                    ->delete();
                //chen moi
                self::insert($insertData);

                return true;
            }
        }
        return false;
    }
    public static function getCollection($id){
        return self::where('product_id', '=', $id)->get();
    }
}