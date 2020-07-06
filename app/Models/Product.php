<?php

namespace App\Models;

use App\Traits\FullTextSearch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Type;

class Product extends Model
{
    use FullTextSearch;
    protected $table = 'products';
    public $timestamps = false;
    const KEY = 'products';
    const KEY_COOKIE_PRDS_HISTORY = 'COOKIE_HISTORY_PRODUCT_';
    protected $searchable = [
        'title',
        'parameter',
//
    ];

    public static function orderClauseText(){
        return [
            2 => 'Bán chạy',
            4 => 'Giá thấp đến cao',
            5 => 'Giá cao đến thấp'
        ];
    }

    public static $orderClause = [
        2 => 'products.is_selling desc, products.created desc', // san pham ban chay
        4 => 'products.price asc,products.created desc',//gia thap den dao
        5 => 'products.price desc,products.created desc',// gias cao den thap
    ];

    static function getById($id,$linked_prds = false) {
        $wery = self::with(['images' => function($q) {
            $q->orderBy('sort','desc');
        },'category']);
        $wery->where('id',$id);
        $wery->where('status',2);
        return $wery->first();
    }

    static function getByAlias($alias,$linked_prds = false) {
        $wery = self::with(['images' => function($q) {
            $q->orderBy('sort','desc');
        },'category', 'product_relates','detail']);
        $wery->where('alias',$alias);
        $wery->where('status',2);
        return $wery;
    }

    public static function getForAdminChoose($name = '',$cate_id = 0,$perpage = 20,$current_page = 0) {

        $cates = $cate_id > 0 ? Category::where(function ($q) use ($cate_id) {
            $q->where('id', $cate_id);
            $q->orWhere('pid', $cate_id);
        })
            ->where('status', 1)
            ->get()->keyBy('id') : '';

        $wery = $cates && !$cates->isEmpty() ? self::whereIn('cat_id', array_keys($cates->toArray())) : self::whereRaw(DB::raw('1 = 1'));

        $wery->with('category');

        if($name != '') {
            $wery->where('title', 'LIKE', "%$name%");
        }
        $wery->orderBy('title');
        if($current_page > 0) {
            Paginator::currentPageResolver(function () use ($current_page) {
                return $current_page;
            });
        }

        return $wery->paginate($perpage);
    }

    public static function getHotCate($limit = 3){
        $data = self::where('lang', \Lib::getDefaultLang())
            ->where('status', 2)
            ->where('hot', 1);
        $data = $data->inRandomOrder();
        if($limit > 0) {
            $data = $data->limit($limit);
        }
        return $data->get();
    }

    public static function getListSale($cat = 0, $limit = 16){
        $data = self::where('lang', \Lib::getDefaultLang())
            ->where('status', 2)
            ->where('is_sale', 1);
        if($cat > 0){
            $data = $data->where('cat_id', $cat);
        }
        //$data = $data->inRandomOrder();
        $data->orderBy('created','desc');
        $data->orderBy('updated','desc');
        if($limit > 0) {
            $data = $data->limit($limit);
        }
        return $data->get();
    }

    public static function getListToday($cat = 0, $limit = 9){
        $data = self::where('lang', \Lib::getDefaultLang())
            ->where('status', 2)
            ->where('is_new', 1);
        if($cat > 0){
            $data = $data->where('cat_id', $cat);
        }
        //$data = $data->inRandomOrder();
        $data->orderBy('updated','desc');
        if($limit > 0) {
            $data = $data->limit($limit);
        }
        return $data->get();
    }

    public static function getByCate($cate_id, $ins = 0,$filter_child = '',$with_filter = false,$keyword = '',$order_by = 0) {
        $cates = $cate_id > 0 ? Category::where('status', 1)
            ->where(function ($q) use ($cate_id) {
                $q->where('id', $cate_id);
                $q->orWhere('pid', $cate_id);
            })
            ->get()->keyBy('id') : Category::where('status', 1)->get()->keyBy('id');
        $wery = \DB::table('products');
        $wery->leftJoin('categories', 'categories.id', '=', 'products.cat_id');
        $wery->join('product_relates', 'product_relates.product_id', '=', 'products.id');
        $wery->select('categories.title');

        if ($with_filter){
            $filter = Filter::getFiltersByArrID($with_filter);
            $filter_cate = [];
            foreach ($filter as $key => $item){
                $filter_cate[$item['filter_cate_id']][] = $item['id'];
            }
        }

        $wery->leftJoin('filter_details','products.id', '=', 'filter_details.object_id');
        if (!empty($ins)){
            $wery->where('products.is_sale', '>', 0);
            $wery->where('products.cat_ins', $ins);
        }
        $wery->select('products.*', DB::raw("(SELECT count(product_id) AS count FROM product_relates WHERE product_id = products.id) as count"));
//        if(is_array($with_filter) && !empty($with_filter)) {
//            $wery->whereIn('filter_details.filter_id', $with_filter);
//        }
//        if($filter_child != ''){
//            $wery->where('categories.slug', '=', $filter_child);
//        }

//        $loop $filter_cate {
//            $wery->wherefilter_detail Ơ
//        }
        if($with_filter && $filter_child) {
            $wery->where(function($q) use($with_filter,$filter_cate,$filter_child) {
                if($with_filter){
                    $wery_select = [];
                    foreach ($filter_cate as $key => $item_filter_by_cate){
                        if (is_array($item_filter_by_cate) && !empty($item_filter_by_cate)){
                            $wery_select[$key] = "(select * from filter_details where filter_id in (".implode(',',$item_filter_by_cate).")) as `$key`";
                        }
                    }
                    if(!empty($wery_select)) {
                        $wery_search  = '';//implode(' inner join ',$wery_select);
                        $i = 1;
                        $temp_join = '';
                        foreach($wery_select as $key => $value){
                            if($i == 1) {
                                $wery_search .= $value;
                                $temp_join = "`$key`";
                            }else {
                                $wery_search .= ' inner join '.$value;
                            }
                            if($i == 2){
                                $wery_search .= " ON ".($temp_join ? $temp_join.'.object_id' : 'object_id')." = `$key`.object_id";
                                $i = 1;
                            }
                            $i++;
                        }
                        $wery_search = "select ".($temp_join ? $temp_join.'.object_id' : 'object_id')." from ($wery_search)";
                        $q->whereRaw("products.id in ($wery_search)");
                    }
                }
                if($filter_child != ''){
                    $q->where('categories.slug', '=', $filter_child);
                }
            });
        }else {
            if($with_filter){
                $wery_select = [];
                foreach ($filter_cate as $key => $item_filter_by_cate){
                    if (is_array($item_filter_by_cate) && !empty($item_filter_by_cate)){
                        $wery_select[$key] = "(select * from filter_details where filter_id in (".implode(',',$item_filter_by_cate).")) as `$key`";
                    }
                }
                if(!empty($wery_select)) {
                    $wery_search  = '';//implode(' inner join ',$wery_select);
                    $i = 1;
                    $temp_join = '';
                    foreach($wery_select as $key => $value){
                        if($i == 1) {
                            $wery_search .= $value;
                            $temp_join = "`$key`";
                        }else {
                            $wery_search .= ' inner join '.$value;
                        }
                        if($i == 2){
                            $wery_search .= " ON ".($temp_join ? $temp_join.'.object_id' : 'object_id')." = `$key`.object_id";
                            $i = 1;
                        }
                        $i++;
                    }
                    $wery_search = "select ".($temp_join ? $temp_join.'.object_id' : 'object_id')." from ($wery_search)";
                    $wery->whereRaw("products.id in ($wery_search)");
                }
            }elseif($filter_child) {
                $wery->where('categories.slug', $filter_child);

            }
        }
        if($keyword != '') {
            $wery->where('products.title','LIKE','%'.$keyword.'%');
        }

        $wery->where('products.status',2);

        $wery->where('products.lang', \Lib::getDefaultLang());
        if($cates && !$cates->isEmpty() ) {
            $wery->whereIn('products.cat_id', array_keys($cates->toArray()));
        }


        if($order_by != '' && in_array($order_by,array_keys(Product::$orderClause))) {
//            if ($order_by == 2){
//                $wery->where('is_selling','>', 0);
//            }
//            else{
                $wery->orderByRaw(Product::$orderClause[$order_by]);
//            }

        }
        $wery->groupBy('products.id');
//        return $wery->paginate($perpage)->appends(Input::except('page'));
        return $wery;

//        return false;
    }

    public static function getByTitle($perpage = 18,$keyword = '',$order_by = 0){
        $wery = \DB::table('products');
        if($keyword != '') {
            $wery->where('products.title','LIKE','%'.$keyword.'%');
        }
        $wery->where('products.status',2);
        $wery->where('products.lang', \Lib::getDefaultLang());
        if($order_by != '' && in_array($order_by,array_keys(Product::$orderClause))) {
            $wery->orderByRaw(Product::$orderClause[$order_by]);
        }
        return $wery->paginate($perpage);

    }

    public static function getByPriceFilterKey($id = 0,$filter_key = '',$quantity = 0)
    {
        $arr_select = ['products.id','products.title', 'products.alias','products.image', 'products.out_of_stock'];
        $wery = \DB::table('products');
        $wery->where('products.id',$id);
        $wery->where('products.status', '>', 1);
        if($filter_key != '') {
            $arr_select = array_merge($arr_select,['product_prices.id as prd_price_id','product_prices.price','product_prices.price_strike']);
            $wery->join('product_prices','products.id', '=', 'product_prices.product_id');
            $wery->where('product_prices.filter_ids', $filter_key);
        }else {
            $arr_select = array_merge($arr_select,['products.price','products.priceStrike']);
        }
        $wery->select($arr_select);
//        $wery->where('product_prices.quantity','>',$quantity-1);

        $product = $wery->first();
//dd($product);
        if(!empty($product)) {
            $wery2 = DB::table('storage');
            if($filter_key != '') {
                $wery2->join('product_prices','product_prices.id','=','storage.prd_price_id');
                $wery2->where('storage.prd_price_id', $product->prd_price_id);
            }else {
                $wery2->where('storage.product_id',$product->id);
            }
            $wery2->select(DB::raw('sum(storage.amount) as total'));
            $storage = $wery2->first();
            if(!empty($storage) && $storage->total >= $quantity) {
                $product->total = $storage->total;
                return $product;
            }else {
                $product->total = 1;
                return $product;
            }
        }
        return false;
    }


    public static function getAllCollectionByID($id)
    {
        $wery = \DB::table('products')
            ->leftJoin('product_collection','product_collection.product_id', '=', 'products.id')
            ->leftJoin('collection','collection.id', '=', 'product_collection.collection_id')
            ->select('collection.id')
            ->where('products.status', '>', 0)
            ->where('product_collection.product_id', $id)
            ->where('collection.status', '>', 0)
            ->get()->toArray();
        return $wery;
    }

    public static function maybeInteresting($limit = 4) {
        return self::where('status', 2)
            ->where('lang', \Lib::getDefaultLang())
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    public static function getAllCateFromPrdIds($prd_ids = [])
    {
        $output = [];
        foreach($prd_ids as $prd_id) {
            $output[$prd_id] = [];
            $wery = \DB::table('products');
            $wery->select('cat_id');
            $wery->where('id',$prd_id);
            $result = $wery->pluck('cat_id')->toArray();

            if(!empty($result)) {
                self::getCateIds($result,$output[$prd_id]);
            }
        }
        return $output;
    }

    public static function getCateIds($cate_ids = [],&$output = []){
        $wery = \DB::table('categories');
        $wery->select('id','pid');
        $wery->whereIn('id',$cate_ids);
        $result = $wery->get();

        if(!empty($result)) {
            $has_pid = [];
            foreach($result as $item) {
                if($item->pid != 0) {
                    $has_pid[] = $item->pid;
                }
                $output[] = $item->id;
            }
            if(!empty($has_pid)) {
                self::getCateIds($has_pid,$output);
            }
        }
    }

    public static function prdHistory($limit = 8)
    {
        $prds_cookie = Cookie::get(Product::KEY_COOKIE_PRDS_HISTORY, []);
        $prds_cookie = !empty($prds_cookie) ? unserialize($prds_cookie) : [];
        if(!empty($prds_cookie)) {
            return self::where('status', 2)
                ->where('lang', \Lib::getDefaultLang())
                ->whereIn('id',$prds_cookie)
                ->limit($limit)
                ->get();
        }
        return [];
    }

    public static function savePrdAfterView($id = 0) {
        $prds_cookie = Cookie::get(Product::KEY_COOKIE_PRDS_HISTORY, []);
        $prds_cookie = !empty($prds_cookie) ? unserialize($prds_cookie) : [];

        if(count($prds_cookie) > 0 && count($prds_cookie) < 10){
            $prds_cookie = array_splice($prds_cookie, 0, 1);
        }
        $prds_cookie[] = $id;
        $prds_cookie = serialize(array_unique($prds_cookie));
        Cookie::queue(Product::KEY_COOKIE_PRDS_HISTORY, $prds_cookie, 60*24*365);
    }

    public function images() {
        return $this->hasMany('App\Models\ProductImage', 'object_id', 'id')->where('type','product');
    }

    public static function getImageGallery($hotel_id = 0,$type="hotel", $json = false){
        $images = ProductImage::where('type',$type)->where('object_id', $hotel_id)->orderByRaw('sort desc,created desc')->get();
        $data = [];
        foreach($images as $image){
            $tmp = $image->toArray();
            $tmp['img'] = $image->image;
            $tmp['image_sm'] = $image->getImageUrl('hotel_small');
            $tmp['image_md'] = $image->getImageUrl('hotel_preview');
            $tmp['image'] = $image->getImageUrl('hotel_large');
            $tmp['image_org'] = $image->getImageUrl();
            array_push($data, $tmp);
        }
        return $json ? json_encode($data) : $data;
    }

    public function getImageUrl($size = 'original'){
        return \ImageURL::getImageUrl($this->image, self::KEY, $size);
    }

    public function getImageHome($size = 'original'){
        return \ImageURL::getImageUrl($this->image_home, self::KEY, $size);
    }

    public function getSubImageUrl($size = 'original'){
        return \ImageURL::getImageUrl($this->sub_image, self::KEY, $size);
    }
    public function getImageBox($size = 'original'){
        return \ImageURL::getImageUrl($this->image_box, self::KEY, $size);
    }

    public static function getImage($img,$size = 'original'){
        return \ImageURL::getImageUrl($img, self::KEY, $size);
    }

    public function getImageSeoUrl($size = 'seo'){
        return \ImageURL::getImageUrl($this->image_seo, self::KEY, $size);
    }



    public function price_format($strike = false){
        $price = $strike ? $this->priceStrike : $this->price;
        return $price > 0 ? number_format($price) . ' ' . $this->unit : '';
    }

    public function price_format_nounit($strike = false,  $unit = true){
        $price = $strike ? $this->priceStrike : $this->price;
        return $price > 0 ? ($unit ? \Lib::priceFormatEdit($price, \Lib::getSiteConfig('currency_short', 'đ'))['price'].'<sup class="text-danger">đ</sup>' : number_format($price)) : '';
    }

    public function get_link(){
        return self::getLinkDetail(str_slug($this->title), $this->id);
    }

    public function linked_prds() {

        if($this->linked_prds) {
            $wery = self::where('status', 2);

            $wery->whereIn('id', explode(',', $this->linked_prds));

            return $wery->get();
        }
        return false;
    }

    public static function getLinkDetail($title_seo = '', $id = 0){
        return route( self::KEY.'.detail', ['safe_title' => str_slug($title_seo), 'id' => $id]);
    }
    public function lang(){ 
        $lang = config('app.locales');
        return isset($lang[$this->lang]) ? $lang[$this->lang] : 'vi';
    }

    public function category(){
        return $this->hasOne('App\Models\Category', 'id', 'cat_id');
    }
    public function product_relates(){
        return $this->hasMany(ProductRelate::class, 'product_id', 'id');
    }

    // public function collection(){
    //     return $this->hasOne('App\Models\Collection', 'id', 'collec_id');
    // }

    public function collections(){
        return $this->belongsToMany(Collection::class, 'product_collection', 'product_id', 'collection_id');
    }

    public function prices()
    {
        return $this->hasMany('App\Models\ProductPrices','product_id','id');
    }

    public function filter_details()
    {
        return $this->hasMany('App\Models\FilterDetail','object_id','id')->where('type','product');
    }
    public function store()
    {
        return $this->hasMany(Storage::class,'product_id','id');
    }

    public function detail()
    {
        return $this->hasOne('App\Models\ProductDetail','product_id','id');
    }

    public function sale(){
        return $this->hasOne(ProductisSale::class, 'product_id', 'id');
    }

    public function relates()
    {
        return $this->belongsToMany('App\Models\Product',(new ProductRelate)->getTable(),'product_id','id_relate');
    }

    public function fb_date(){
        return [
            'created' => date('c',$this->created),
            'published' => date('c',$this->startTime),
            'update' => date('c',$this->startTime),
        ];
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
    
    public function warehouse(){
        return $this->belongsTo(Storage::class);
    }

    public function collection()
    {
        return $this->belongsToMany(Collection::class, 'product_collection', 'product_id', 'collection_id');
    }

    public static function getProductsByTags(){
        $data = \DB::table('products')
            ->join('tags', 'tags.id', '=', 'products.special_box_home')
            ->select('tags.id')
            ->select('products.*')
            ->where('tags.type', 2)
            ->where('tags.is_special_box',1)
            ->where('tags.status',1)
            ->where('products.status', '>', 1);
        return $data;
    }
    public static function getProductsByCate($cate_id = 0,$ins = 0,$with_filter = false,$keyword = '',$order_by = 0){
        $cates = $cate_id > 0 ? Category::where('status', 1)
            ->where(function ($q) use ($cate_id) {
                $q->where('id', $cate_id);
                $q->orWhere('pid', $cate_id);
            })
            ->get()->keyBy('id') : Category::where('status', 1)->get()->keyBy('id');
        $wery = \DB::table('products');
        $wery->leftJoin('categories', 'categories.id', '=', 'products.cat_id');
        $wery->select('categories.title');
        $wery->leftJoin('filter_details','products.id', '=', 'filter_details.object_id');
        if (!empty($ins)){
            $wery->where('products.is_sale', '>', 0);
            $wery->where('products.cat_ins', $ins);
        }
        $wery->select('products.id', 'products.title', 'products.image', 'products.price', 'products.alias', 'products.is_tragop', 'products.parameter', 'products.rate_avg', 'products.priceStrike', 'products.out_of_stock');
        if(is_array($with_filter) && !empty($with_filter)) {
            $wery->whereIn('filter_details.filter_id', $with_filter);
        }
        if($keyword != '') {
            $wery->where('products.title','LIKE','%'.$keyword.'%');
        }
        $wery->where('products.status',2);
        $wery->where('products.lang', \Lib::getDefaultLang());
        if($cates && !$cates->isEmpty()) {
            $wery->whereIn('products.cat_id', array_keys($cates->toArray()));
        }
        if($order_by != '' && in_array($order_by,array_keys(Product::$orderClause))) {
            if ($order_by == 2){
                $wery->where('is_selling','>', 0);
            }
            else{
                $wery->orderByRaw(Product::$orderClause[$order_by]);
            }

        }
        $wery->groupBy('products.id');
        return $wery;
    }
    public static function getProductsInstallment($cateID = 0, $productID = 0, $key = '', $limit = 0){
        $data = self::where('cat_id', $cateID)
            ->where('id', '<>', $productID)
            ->where('title', 'LIKE','%'.$key.'%')
            ->where('status', '>', 1)
            ->limit($limit)
            ->select('id', 'title', 'alias', 'image')
            ->get();
        return $data;
    }

    public static function getProductsIns($ins, $cate_id,$perpage = 18, $with_filter = false, $order_by = 0){
        $cates = $cate_id > 0 ? Category::where('status', 1)
            ->where(function ($q) use ($cate_id) {
                $q->where('id', $cate_id);
                $q->orWhere('pid', $cate_id);
            })
            ->get()->keyBy('id') : Category::where('status', 1)->get()->keyBy('id');
        $data = self::where('status', '>', 1);
        $data->leftJoin('filter_details','products.id', '=', 'filter_details.object_id');

        if($with_filter){
            $data->whereIn('filter_details.filter_id', $with_filter);
            $data->havingRaw('count(distinct filter_details.filter_id) = ' . count($with_filter));
        }else{
            if ($ins != ''){
                $data->where('cat_ins', $ins);
            }
            if($cates && !$cates->isEmpty() ) {
                $data->whereIn('products.cat_id', array_keys($cates->toArray()));
            }
        }
        if($order_by != '' && in_array($order_by,array_keys(Product::$orderClause))) {
            if ($order_by == 2){
                $data->where('is_selling','>', 0);
            }
            else{
                $data->orderByRaw(Product::$orderClause[$order_by]);
            }

        }
        return $data->paginate($perpage)->appends(Input::except('page'));
    }
    public static function getProductSlide($cate_id, $limit = 10){
        $cates = $cate_id > 0 ? Category::where('status', 1)
            ->where(function ($q) use ($cate_id) {
                $q->where('id', $cate_id);
                $q->orWhere('pid', $cate_id);
            })
            ->get()->keyBy('id') : Category::where('status', 1)->get()->keyBy('id');
        $data = self::where('status', '>', 1);
        $data->join('product_relates', 'product_relates.product_id', '=', 'products.id');

        if($cates && !$cates->isEmpty() ) {
            $data->whereIn('products.cat_id', array_keys($cates->toArray()));
        }
//        dd(array_keys($cates->toArray()));
//        dd($data->get());
        $data->select('products.*', DB::raw("(SELECT count(product_id) AS count FROM product_relates WHERE product_id = products.id) as count"));

        $data->limit($limit);
        return $data;
    }

    public static function getProByCate($pid, $limit){
        $data = \DB::table('products')
            ->select('products.*')
            ->where('products.status', '>', 1)
            ->where('products.cat_id', $pid)
            ->limit($limit)
            ->get();
        return $data;
    }
    public static function getParaByPrdId($id){
        $param = \DB::table('products')
            ->where('id', $id)
            ->select('products.parameter')
            ->first();
        $data = !empty($param) ? $param->parameter : '';
        return $data;
    }
    public static function getLineByPrdId($id){
        $param = \DB::table('products')
            ->where('id', $id)
            ->select('products.lineBox')
            ->first();
        $data = !empty($param) ? $param->lineBox : '';
        return $data;
    }
    public static function getProManu($abc = [], $type='products'){
        $data = \DB::table('products')
            ->join('filter_details', 'filter_details.object_id', '=', 'products.id')
            ->join('filters', 'filters.id', '=', 'filter_details.filter_id')
            ->whereIn('filters.id', $abc)
            ->select('products.*');
        return $data;
    }

    public static function getProductCompare($id, $catid, $p_start, $p_end, $key = '', $limit){
        $data= \DB::table('products')
            ->leftJoin('categories', 'categories.id', '=', 'products.cat_id')
            ->where('categories.id', '=', $catid)
            ->whereBetween('products.price', [$p_end, $p_start])
            ->where('products.id', '<>', $id)
            ->where('products.status', '>', 1)
            ->select('products.id', 'products.title', 'products.alias', 'products.parameter', 'products.image', 'products.price', 'products.out_of_stock')
            ->limit($limit);
        if ($key != ''){
            $data->where('products.title','like', '%' . $key . '%');
        }
        return $data;
    }

    public static  function getProductAccessoryByCate($id, $catid, $limit){
        $data= \DB::table('products')
            ->leftJoin('categories', 'categories.id', '=', 'products.cat_id')
            ->where('categories.id', '=', $catid)
            ->where('products.id', '<>', $id)
            ->where('products.status', '>', 1)
            ->select('products.id', 'products.title', 'products.alias', 'products.parameter', 'products.image', 'products.price', 'products.out_of_stock')
            ->orderBy('id')
            ->limit($limit);
        return $data;
    }

    static  function getProByAliasArr($alias, $limit){
        $wery = self::with(['images' => function($q) {
            $q->orderBy('sort','desc');
        },'category', 'product_relates']);
        $wery->whereIn('alias',$alias);
        $wery->where('status',2);
        $wery->limit($limit);
        return $wery;
    }

    public static function getPrqoductByCollect($collect_id){
        $data = self::leftJoin('product_collection', 'product_collection.product_id', '=', 'products.id')
            ->join('product_relates', 'product_relates.product_id', '=', 'products.id')
            ->where('product_collection.collection_id', $collect_id)
            ->where('products.status', '>', 1)
            ->select('products.id', 'products.alias', 'products.title', 'products.priceStrike', 'products.price', 'products.rate_avg', 'products.image', 'products.parameter', 'products.out_of_stock', 'products.option', 'product_collection.collection_id as collect_id', DB::raw("(SELECT count(product_id) AS count FROM product_relates WHERE product_id = products.id) as count") );
        return $data;
    }

    public static function getProductsByKey($key = '', $key_int = [], $order_by = 0, $limit = 0){
        $wery = Product::withCount('product_relates');
        if (!empty($key_int)){
            foreach($key_int as $k => $element) {
                $wery->Where('title', 'like', '%' . $element . '%');
            }
        }
        if ($key !=  ''){
            $wery->search(''.$key.'');
        }
        $wery->where('status','>', 1);
        if($order_by != '' && in_array($order_by,array_keys(Product::$orderClause))) {
            $wery->orderByRaw(Product::$orderClause[$order_by]);
        }
        if ($limit != 0){
            $wery->limit($limit);
        }
        return $wery;

    }
    public static function getProductsByKeyInt($key = [], $order_by = 0, $limit = 0, $arr = false){
        $wery = Product::withCount('product_relates')->where('status','>', 1);

        foreach($key as $k => $element) {
            $wery->Where('title', 'like', '%' . $element . '%');
        }
        if($order_by != '' && in_array($order_by,array_keys(Product::$orderClause))) {
            $wery->orderByRaw(Product::$orderClause[$order_by]);
        }
        if ($limit != 0){
            $wery->limit($limit);
        }
        return $wery;
    }



}

