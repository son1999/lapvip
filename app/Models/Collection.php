<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Collection extends Model {


    protected $table = 'collection';
    public $timestamps = false;
    protected $fillable = ['id', 'title', 'image', 'status', 'created','lang', 'cat_id'];
    const KEY = 'collection';

    public static function getCollectByCat($cat)
    {
        $wery = \DB::table('collection')
        ->join('collection_cate_pivot', 'collection_cate_pivot.collect_id', '=', 'collection.id')
        ->join('product_collection', 'product_collection.collection_id', '=', 'collection.id')
        ->join('products', 'products.id', '=', 'product_collection.product_id')
        ->where('collection_cate_pivot.cat_id', $cat)
        ->where('collection.status', '>', 0)
        ->select('collection.*')
        ->select('products.*')
        ->get();
        return $wery;
    }

    public function lang(){
        $lang = config('app.locales');
        return isset($lang[$this->lang]) ? $lang[$this->lang] : 'vi';
    }

    public function products() 
    {
        return $this->belongsToMany(Product::class, 'product_collection', 'collection_id', 'product_id');
    }
    public function cates()
    {
        return $this->belongsToMany(Category::class, 'collection_cate_pivot', 'collect_id', 'cat_id');
    }
    public static function getCollec($type = 0, $lang = '', $imgSize = ''){
        if(empty($lang)){
            $lang = \Lib::getDefaultLang();
        }
        return self::where('status', '>', 0)->get();
    }

    public function collectpivot(){
        return $this->hasMany(CollectionPivot::class, 'collect_id', 'id');
    }

    public static function getByCateId($id = 0){
       $wery = \DB::table('collection')
           ->leftJoin('collection_cate_pivot', 'collection_cate_pivot.collect_id', '=', 'collection.id')
           ->where('collection_cate_pivot.cat_id', $id)
           ->where('collection.status', 1)
           ->get();

       return $wery;
    }

    public static  function getCollectionByCat($cat_id){
        $data = self::leftJoin('collection_cate_pivot', 'collection_cate_pivot.collect_id', '=', 'collection.id')
            ->where('collection_cate_pivot.cat_id', $cat_id)
            ->select('collection.id', 'collection.title', 'collection.cat_id', 'collection.filter_id', 'collection_cate_pivot.collect_id as collec_cate')
            ->get()->toArray();
        return $data;
    }

}