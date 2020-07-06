<?php

namespace App\Modules\BackEnd\Controllers;

use App\Libs\LoadDynamicRouter;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Filter;
use App\Models\FilterCate;
use App\Models\FilterDetail;
use App\Models\Product;
use App\Models\ProductCompareTemplate;
use App\Models\ProductDetail;
use App\Models\ProductImage;
use App\Models\ProductisSale;
use App\Models\ProductPrices;
use App\Models\ProductCollection;
use App\Models\ProductSpecsTemplate;
use App\Models\Storage;
use App\Models\ProductRelate;
use App\Models\Tag;
use App\Models\TagDetail;
use App\Models\Warehouse;
use App\Transformers\PrdPriceTranformer;
use League\Fractal;
use Illuminate\Http\Request;

use App\Models\Product as THIS;
use function App\Models\ProductRelate;

class ProductController extends BackendController
{
    //config controller, ez for copying and paste
    protected $timeStamp = 'created';
    protected $tagID = 2;
    protected $filter_type = 'product';

    public function __construct(){
        parent::__construct(new THIS(),[[
            'title' => 'required|max:500',
            'alias' => 'required',
            'cat_id' => 'required',
            'image' => 'nullable|mimes:jpeg,jpg,png,gif',
            'image_seo' => 'nullable|mimes:jpeg,jpg,png,gif'
        ]]);
        $this->folder_upload = Product::KEY;
        LoadDynamicRouter::loadRoutesFrom('FrontEnd');

        \View::share('collec', Collection::getCollec());
        \View::share('catOpt', Category::getCat(1));
        \View::share('catIns', Category::getCat(3));
        \View::share('tagType', $this->tagID);
        \View::share('filterType', $this->filter_type);
        $this->registerAjax('selling', 'ajaxSelling', 'edit');
        $this->registerAjax('heightClass', 'ajaxHeightClass', 'edit');
        $this->registerAjax('load', 'ajaxImageLoad', 'view');
        $this->registerAjax('upload_img', 'ajaxItemUploadMulti', 'add');
        $this->registerAjax('remove_img', 'ajaxItemImgDel', 'delete');
        $this->registerAjax('change-pos', 'ajaxItemChangePos', 'edit');
        $this->registerAjax('out_of_stock', 'ajaxOutOfStock', 'edit');
        $this->registerAjax('get-filter-cate-by-prd-cate', 'ajaxFilterCateByPrdCate', 'edit');
        $this->registerAjax('get-all-specs-temp', 'ajaxSpecsTemplate');
        $this->registerAjax('get-all-compare-temp', 'ajaxCompareTemplate');
        $this->registerAjax('get-specs-temp-by-id', 'ajaxSpecsTempById');
        $this->registerAjax('get-com-temp-by-id', 'ajaxCompareTempById');
        $this->registerAjax('get-all-relates', 'ajaxAllRelates');
        $this->registerAjax('get-all-Parameter', 'ajaxAllParameter');
        $this->registerAjax('get-all-LineBox', 'ajaxAllLineBox');
        $this->registerAjax('duplicate', 'ajaxCreatedDuplicateProduct');
    }

    public function index(Request $request){
        $cond = [];
        if($request->id != ''){
            $cond[] = ['id', $request->id];
        }else {
            if ($request->status != '') {
                $cond[] = ['status', $request->status];
            } else {
                $cond[] = ['status', '>', 0];
            }
            if ($request->lang != '') {
                $cond[] = ['lang', '=', $request->lang];
            }
            if ($request->cat_id != '') {
                $cond[] = ['cat_id', $request->cat_id];
            }
            if ($request->collec_id != '') {
                $cond[] = ['collec_id', $request->collec_id];
            }
            if ($request->title != '') {
                $cond[] = ['title', 'LIKE', '%' . $request->title . '%'];
            }
            if(!empty($request->time_from)){
                $timeStamp = \Lib::getTimestampFromVNDate($request->time_from);
                array_push($cond, ['created', '>=', $timeStamp]);
            }
            if(!empty($request->time_to)){
                $timeStamp = \Lib::getTimestampFromVNDate($request->time_to, true);
                array_push($cond, ['created', '<=', $timeStamp]);
            }
        }

        $data = THIS::with(['category'])->where($cond)->orderByRaw('created DESC, id DESC')->paginate($this->recperpage);
        return $this->returnView('index', [
            'data' => $data,
            'search_data' => $request,
        ]);
    }
    public function showAddForm(){
        $warehouse = Warehouse::getWarehousePro();
        $special_tags = Tag::getProductSpecialTags();
        return $this->returnView($this->bladeAdd,[
            'warehouse' => $warehouse,
            'filter_cate_price' => [],
            'collection' => [],
            'filter_cate_not_price' => [],
            'special_tags' => $special_tags,
        ]);
    }
    public function showEditForm($id){
        $data = THIS::with('detail')->find($id);
//        dd($data);
        set_old($data);
        if(empty($data)){
            return $this->notfound($id);
        }
        $tags = Tag::getProductTags($id,true);
        $special_tags = Tag::getProductSpecialTags($id);
        if(!empty($tags)){
            $tmp = [];
            foreach ($tags as $item){
                $tmp[] = $item->title;
            }
            $tags = implode(',', $tmp);
        }else{
            $tags = '';
        }

        $fractal = new Fractal\Manager();
        $obj = ProductPrices::getByPrdId($id,true);

        $warehouse = Warehouse::getWarehousePro();
        $filter_cate_price = FilterCate::getFilterCates(true ,$data->cat_id);
//        $filter_cate_not_price = FilterCate::getFilterCates(false,$data->cat_id)->keyBy('id')->toArray();
        $filter_cate_not_price = FilterCate::getFilterCatess(false,$data->cat_id)->keyBy('id')->toArray();
        $filter = Filter::getFilters();
        $fil = FilterCate::fetchResult($filter);
        foreach ($filter_cate_not_price as &$item){
            foreach ($fil as $item_fil){
                if ($item['id'] == $item_fil['filter_cate_id']){
                    $item['filters'][] = $item_fil;
                }
            }
        };
        $collection = Collection::getByCateId($data->cat_id)->keyBy('id')->toArray();
        $collect = ProductCollection::getCollection($id)->keyBy('collection_id')->toArray();
        $filter_prd = FilterDetail::getFilters($id)->keyBy('filter_id')->toArray();
//        dd($collect, $collection);
        foreach($filter_cate_not_price as &$item) {
            foreach($item['filters'] as &$item_filter) {
                if (!empty($item_filter['sub'])){
                    foreach ($item_filter['sub'] as &$item_filter_sub){
                        if(isset($filter_prd[$item_filter_sub['id']])) {
                            $item_filter_sub['checked'] = 1;
                        }else {
                            $item_filter_sub['checked'] = 0;
                        }
                    }
                }
                if(isset($filter_prd[$item_filter['id']])) {
                    $item_filter['checked'] = 1;
                }else {
                    $item_filter['checked'] = 0;
                }
            }
        }
        foreach($collection as &$item_collection) {
            if(isset($collect[$item_collection->collect_id])) {
                $item_collection->checked = 1;
            }else {
                $item_collection->checked= 0;
            }
        }
//        dd(json_encode((object)$filter_cate_not_price));
        return $this->returnView('edit', [
            'data' => $data,
            'tags' => $tags,
            'collections' => Product::getAllCollectionByID($id),
            'collection' => $collection,
            'special_tags' => $special_tags,
            'warehouse' => $warehouse,
            'filter_cate_price' => $filter_cate_price->toArray(),
            'filter_cate_not_price' => (object)$filter_cate_not_price,
//            'filter_cates' => FilterCate::getFilterCates(true),
            'product_prices' => $fractal->createData(new Fractal\Resource\Collection($obj['prices'], new PrdPriceTranformer))->toArray(),
//            'filter_cates' => FilterCate::getFilterCates(true),
        ]);
    }

    public function beforeSave(Request $request, $ignore_ext = [])
    {
        $arr_unset = ['tags','collection_id','filters','uploadify_hotel_img','img_upload_for_add','filter_cate_','filter_ids','filter_price_ids',
            'filters_','prices','priceStrikes','base_price','base_priceStrike','quantity','relate_titles_','relate_values_'];
        if($this->editMode) {
            TagDetail::where('tag_id', $this->model->special_box_home)->where('object_id',$this->model->id)->where('type',2)->delete();
        }

        parent::beforeSave($request); // TODO: Change the autogenerated stub
        $this->uploadImage($request, $request->title.'-seo', 'image_seo');
        $this->uploadImage($request, $request->title.'-sub', 'sub_image');
        $this->uploadImage($request, $request->title.'-home', 'image_home');

        $this->model->price = intval(str_replace([',','.'], '', $this->model->base_price));
        $this->model->priceStrike = intval(str_replace([',','.'], '', $this->model->base_priceStrike));
        $this->model->parameter = implode('|', $request->parameter);
//        $this->model->lineBox = implode('|', $request->lineBox);
        $this->uploadImage($request, $request->title.'-box', 'image_box');
        if(isset($request->id) && $request->id > 0){
            $this->model->updated = time();
        }
//        dd(ProductSpecsTemplate::returnProperties($request,$arr_unset));

        $this->process_laters->properties = ProductSpecsTemplate::returnProperties($request,$arr_unset);
//        $this->process_laters->comperties = ProductCompareTemplate::returnProperties($request,$arr_unset);
//        $this->process_laters->promote_props = ProductSpecsTemplate::returnPromoteProperties($request,$arr_unset);

        $this->unsetFields($arr_unset);

    }

    public function afterSave(Request $request)
    {
//        if(!empty($request->collection_id)) {
            $collec = $request->collection_id;
            $this->model->collections()->sync($collec);
//            ProductCollection::addCollections($request->collection_id, $this->editID);
//        }

        $array_filter_detail = $this->insertFilterPrice($request);

        $array_filter_detail = array_merge($this->insertFilter($request),$array_filter_detail);

        if(!empty($array_filter_detail)) {
            $this->model->filter_details()->saveMany($array_filter_detail);
        }

        if($this->editMode && !empty($this->model->detail)) {
            $detail = ['properties' => $this->process_laters->properties];
            $this->model->detail()->update($detail);
        }else {
            $detail = new ProductDetail(['properties' => $this->process_laters->properties]);
            $this->model->detail()->save($detail);
        }


        $relate_titles = $request->input('relate_titles_');
        if(!empty($relate_titles)) {
            $relate_values = $request->input('relate_values_');
            $arr_temp = [];
            for($i = 0;$i<count($relate_titles);$i++) {
                $arr_temp[$relate_values[$i]] = ['title' => $relate_titles[$i]];
            }
            $this->model->relates()->sync($arr_temp);
        }

        if($this->model->special_box_home > 0) {
            $tag_Detail = new TagDetail(['tag_id' => $this->model->special_box_home,'type' => 2,'object_id' => $this->model->id]);
            $tag_Detail->save();
        }


        if(!empty($request->img_upload_for_add)) {
            ProductImage::whereIn('id',explode(',',$request->img_upload_for_add))->update(['object_id'=>$this->model->id]);
        }
    }

    private function insertFilterPrice(Request $request) {

        if($request->input('filter_price_ids')) {
            $filters_price = $request->input('filter_price_ids');
            $prices = $request->input('prices');
            $priceStrike = $request->input('priceStrikes');
            $quantity = $request->input('quantity');
            $arr_price = [];
            $arr_filter_ids = [];

            ProductPrices::where('product_id',$this->model->id)->delete();
            Storage::where('product_id',$this->model->id)->delete();
            for($i = 0; $i <= sizeof($filters_price) - 1; $i++) {
                $prd_price = new ProductPrices();
                $prd_price->filter_ids = $filters_price[$i];
                $arr_filter_ids = array_merge($arr_filter_ids,array_map(function($b){
                    return ['for_price' => 1, 'filter_id' => (int)$b];
                },explode(',',$prd_price->filter_ids)));
                $prd_price->price = intval(str_replace([',','.'], '', $prices[$i]));
                $prd_price->price_strike = intval(str_replace([',','.'], '', $priceStrike[$i]));
//                $prd_price->quantity = $quantity[$i];
                $prd_price->created = time();
                $arr_price[] = $prd_price;
            }

            $arr_filter_ids = \Lib::unique_multidim_array($arr_filter_ids,'filter_id');
            if(!empty($arr_filter_ids)) {
                FilterDetail::where('object_id',$this->model->id)->where('type','product')->where('for_price',1)->delete();
                $array_filter_detail = [];
                foreach ($arr_filter_ids as $item) {
                    $fil_detail = new FilterDetail();
                    $fil_detail->filter_id = (int)$item['filter_id'];
                    $fil_detail->for_price = (int)$item['for_price'];
                    $array_filter_detail[] = $fil_detail;
                }
            }

            if(!empty($arr_price)) {

                $this->model->prices()->saveMany($arr_price);
                $this->model->load('prices');

                $load_price = $this->model->prices->toArray();


                if(!empty($load_price)) {
                    $arr_storage = [];
                    for($i = 0 ; $i < count($load_price); $i ++) {
                        foreach($quantity[$i] as $key => $value){
                            $obj['prd_price_id'] = $load_price[$i]['id'];
                            $obj['warehouse_id'] = $key;
                            $obj['amount'] = $value;
                            $obj['product_id'] = $this->model->id;
                            $obj['created'] = time();
                            $arr_storage[] = $obj;
                        }
                    }

                }
                if(isset($arr_storage) && !empty($arr_storage)) {
                    Storage::insert($arr_storage);
                }

//                dd($arr_storage);
            }
        }else {
            ProductPrices::where('product_id',$this->model->id)->delete();
            FilterDetail::where('object_id',$this->model->id)->where('type','product')->where('for_price',1)->delete();
        }
        return isset($array_filter_detail) ? $array_filter_detail : [];
    }

    private function insertFilter(Request $request) {
        if($request->input('filters_')) {
            $arr_filter_ids = [];
            $filters = $request->input('filters_');
            for($i = 0; $i <= sizeof($filters) - 1; $i ++) {
                $arr_filter_ids[] = ['for_price' => 0, 'filter_id' => (int)$filters[$i]];
            }

            $arr_filter_ids = \Lib::unique_multidim_array($arr_filter_ids,'filter_id');
            if(!empty($arr_filter_ids)) {
                FilterDetail::where('object_id',$this->model->id)->where('type','product')->where('for_price',0)->delete();
                $array_filter_detail = [];
                foreach ($arr_filter_ids as $item) {
                    $fil_detail = new FilterDetail();
                    $fil_detail->filter_id = (int)$item['filter_id'];
                    $fil_detail->for_price = (int)$item['for_price'];
                    $array_filter_detail[] = $fil_detail;
                }
            }
        }else {
            FilterDetail::where('object_id',$this->model->id)->where('type','product')->where('for_price',0)->delete();
        }

        return isset($array_filter_detail) ? $array_filter_detail : [];
    }

    protected function ajaxSelling(Request $request){
//        dd($request->set);
        if($request->id > 0) {
            $data = THIS::find($request->id);
            if ($data) {
                $before = $data->is_selling;
                $data->is_selling = $request->set == 1 ? 1 : 0;
                $data->save();
                \MyLog::do()->add('product-selling', $data->id, $data->is_selling, $before);
                return \Lib::ajaxRespond(true, 'success');
            }
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }

    protected function ajaxHeightClass(Request $request){
        if($request->id > 0) {
            $data = THIS::find($request->id);
            if ($data) {
                $before = $data->is_height_class;
                $data->is_height_class = $request->set == 1 ? 1 : 0;
                $data->save();
                // \MyLog::do()->add('product-height-class', $data->id, $data->is_height_class, $before);
                return \Lib::ajaxRespond(true, 'success');
            }
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }

    protected function ajaxItemChangePos(Request $request){
        if($request->id > 0 && $request->next > 0 && $request->type != ''){
            $next = ProductImage::find($request->next);
            $cur  = ProductImage::find($request->id);
            if($next && $cur){
                $cur->sort = $request->type == 'left' ? ($next->sort + 1) : ($next->sort - 1);
                $cur->save();
                return \Lib::ajaxRespond(true, 'ok');
            }
        }
        return \Lib::ajaxRespond(false, 'Dữ liệu không chính xác');
    }

    protected function ajaxItemImgDel(Request $request){
        if($request->id > 0){
            $data = ProductImage::where('id',$request->id)->where('object_id',$request->object_id)->where('type',$request->type)->first();
            if($data){
                $data->delete();
                return \Lib::ajaxRespond(true, 'ok', ['images' => Product::getImageGallery($request->object_id,$request->type)]);
            }
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }

    protected function ajaxImageLoad(Request $request){
        return \Lib::ajaxRespond(true, 'ok', ['images' => Product::getImageGallery($request->object_id,$request->type)]);
    }

    protected function ajaxItemUploadMulti(Request $request){
        if ($request->hasFile('Filedata')) {
            $image = $request->file('Filedata');
            if ($image->isValid()) {
                $title = basename($image->getClientOriginalName(), '.'.$image->getClientOriginalExtension());
                $fname = $this->uploadImage($request, $title, 'Filedata');
                if(!empty($fname)){
                    $imgGallery = new ProductImage();
                    $imgGallery->object_id = $request->object_id;
                    $imgGallery->image = $fname;
//                    $imgGallery->size = $image->getClientSize();
//                    $imgGallery->type = $image->getClientMimeType();
                    $imgGallery->created = time();
                    $imgGallery->type = $request->type;
//                    $imgGallery->changed = time();
                    $imgGallery->user_id = \Auth::id();
//                    $imgGallery->uname = \Auth::user()->user_name;
//                    $imgGallery->lang = $request->lang;
                    $imgGallery->sort = ProductImage::getSortInsert($request->lang);
                    $imgGallery->save();

                    if(empty($imgGallery->object_id)) {
                        return \Lib::ajaxRespond(true, 'ok', ['id' => $imgGallery->id]);
                    }else {
                        return \Lib::ajaxRespond(true, 'ok', ['images' => Product::getImageGallery($request->object_id,$request->type)]);
                    }
                }
                return \Lib::ajaxRespond(false, 'Upload ảnh thất bại!');
            }
            return \Lib::ajaxRespond(false, 'File không hợp lệ!');
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy ảnh!');
    }

    protected function ajaxOutOfStock(Request $request){
//        dd($request->set);
        if($request->id > 0) {
            $data = THIS::find($request->id);
            if ($data) {
                $before = $data->out_of_stock;
                $data->out_of_stock = $request->set == 1 ? 1 : 0;
//                $data->status = $request->set == 0 ? $data->status : 1;
                $data->save();
                \MyLog::do()->add('product-out_of_stock', $data->id, $data->out_of_stock, $before);
                return \Lib::ajaxRespond(true, 'success');
            }
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }

    protected function ajaxFilterCateByPrdCate(Request $request){
        if($request->id > 0) {
            $prd_cate = Category::getCateById($request->id);
            if(!empty($prd_cate)) {
                $filter_cate_price = FilterCate::getFilterCates(true ,$prd_cate->id);
                $filter_cate_not_price = FilterCate::getFilterCatess(false,$prd_cate->id)->keyBy('id')->toArray();
                $filter = Filter::getFilters();
                $fil = FilterCate::fetchResult($filter);
                foreach ($filter_cate_not_price as &$item){
                    foreach ($fil as $item_fil){
                        if ($item['id'] == $item_fil['filter_cate_id']){
                            $item['filters'][] = $item_fil;
                        }
                    }
                }
                return \Lib::ajaxRespond(true, 'success',[
                    'filter_cate_price' => $filter_cate_price,
                    'filter_cate_not_price' => $filter_cate_not_price
                ]);
            }
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }

    protected function ajaxSpecsTemplate(Request $request)
    {
        $temp = ProductSpecsTemplate::getAllFroAdminChoose();
        if(!empty($temp)) {
            return \Lib::ajaxRespond(true, 'success',$temp);
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }
    protected function ajaxCompareTemplate(Request $request)
    {
        $temp = ProductCompareTemplate::getAllFroAdminChoose();
        if(!empty($temp)) {
            return \Lib::ajaxRespond(true, 'success',$temp);
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }

    protected function ajaxSpecsTempById(Request $request)
    {
        $temp = ProductSpecsTemplate::getById($request->input('id'));
        if(!empty($temp)) {
            return \Lib::ajaxRespond(true, 'success',$temp);
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }
    protected function ajaxCompareTempById(Request $request)
    {
        $temp = ProductCompareTemplate::getById($request->input('id'));
        if(!empty($temp)) {
            return \Lib::ajaxRespond(true, 'success',$temp);
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }


    protected function ajaxAllRelates(Request $request)
    {
        $temp = ProductRelate::getByPrdId($request->input('id'));
        if(!empty($temp)) {
            return \Lib::ajaxRespond(true, 'success',$temp);
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }
    protected function ajaxAllParameter(Request $request)
    {
        $temp = Product::getParaByPrdId($request->input('id'));
        if(!empty($temp)) {
            return \Lib::ajaxRespond(true, 'success',$temp);
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }

    protected function ajaxAllLineBox(Request $request)
    {
        $temp = Product::getLineByPrdId($request->input('id'));
        if(!empty($temp)) {
            return \Lib::ajaxRespond(true, 'success',$temp);
        }
        return \Lib::ajaxRespond(false, 'Không tìm thấy dữ liệu');
    }

    protected function ajaxCreatedDuplicateProduct(Request $request){
        $data = Product::find($request->id);
        $product = new Product();
        $product->title = $data['title'];
        $product->title_sub = $data['title_sub'];
        $product->alias = $data['alias'];
        $product->title_seo = $data['title_seo'];
        $product->safe_title = $data['safe_title'];
        $product->status = 1;
        $product->lang = $data['lang'];
        $product->price = $data['price'];
        $product->priceStrike = $data['priceStrike'];
        $product->code = $data['code'];
        $product->unit = $data['unit'];
        $product->sort = $data['sort'];
        $product->sapo = $data['sapo'];
        $product->body = $data['body'];
        $product->sort_body = $data['sort_body'];
        $product->parameter = $data['parameter'];
        $product->hot = $data['hot'];
        $product->cat_id = $data['cat_id'];
        $product->cat_ins = $data['cat_ins'];
        $product->is_sale = $data['is_sale'];
        $product->linked_prds = $data['linked_prds'];
        $product->is_height_class = $data['is_height_class'];
        $product->out_of_stock = $data['out_of_stock'];
        $product->is_selling = $data['is_selling'];
        $product->special_box_home = $data['special_box_home'];
        $product->is_tragop = $data['is_tragop'];
        $product->is_tietkiem = $data['is_tietkiem'];
        $product->link = $data['link'];
        $product->lineBox = $data['lineBox'];
        $product->sale_detail = $data['sale_detail'];
        $product->save();
        if ($product){
            $productDetail  = ProductDetail::where('product_id', '=', $request->id)->first();
            $newproductDetail = new ProductDetail();
            $newproductDetail->product_id = $product->id;
            $newproductDetail->properties = $productDetail['properties'];
            $newproductDetail->comperties = $productDetail['comperties'];
            $newproductDetail->promote_props = $newproductDetail['promote_props'];
            $newproductDetail->save();

            $productRelates = ProductRelate::where('product_id', $request->id)->get();
            $arr_temp = [];
            for($i = 0;$i<count($productRelates);$i++) {
                $arr_temp[$productRelates[$i]['id_relate']] = ['title' => $productRelates[$i]['title']];
            }
            $product->relates()->sync($arr_temp);

            $productCollection = ProductCollection::where('product_id', $request->id)->get();
            $arr_colec = [];
            for($i = 0;$i<count($productCollection);$i++) {
                $arr_colec[] = $productCollection[$i]['collection_id'];
            }
            $product->collections()->sync($arr_colec);

            $productFilter_Detail = FilterDetail::where('object_id', $request->id)->get();
            $arr_filter_ids = [];
            for($i = 0;$i<count($productFilter_Detail);$i++) {
                $arr_filter_ids[] = ['for_price' => $productFilter_Detail[$i]['for_price'], 'filter_id' => $productFilter_Detail[$i]['filter_id']];
            }
            $arr_filter_ids = \Lib::unique_multidim_array($arr_filter_ids,'filter_id');
            if(!empty($arr_filter_ids)) {
                FilterDetail::where('object_id',$product->id)->where('type','product')->where('for_price',0)->delete();
                $array_filter_detail = [];
                foreach ($arr_filter_ids as $item) {
                    $fil_detail = new FilterDetail();
                    $fil_detail->filter_id = (int)$item['filter_id'];
                    $fil_detail->for_price = (int)$item['for_price'];
                    $array_filter_detail[] = $fil_detail;
                }
                $product->filter_details()->saveMany($array_filter_detail);
            }



            $productPrice = ProductPrices::where('product_id', '=', $request->id)->get();
            for($i = 0;$i<count($productPrice);$i++) {
                $arr_price_ids[] = ['filter_ids' => $productPrice[$i]['filter_ids'], 'price' => $productPrice[$i]['price'], 'price_strike' => $productPrice[$i]['price_strike'], 'product_code' => $productPrice[$i]['price_strike'], 'quantity' => $productPrice[$i]['quantity']];
            }
            if(!empty($arr_price_ids)) {
                ProductPrices::where('product_id',$product->id)->delete();
                $arr_price = [];
                foreach ($arr_price_ids as $item) {
                    $fil_price = new ProductPrices();
                    $fil_price->filter_ids = (int)$item['filter_ids'];
                    $fil_price->price = (int)$item['price'];
                    $fil_price->price_strike = (int)$item['price_strike'];
                    $fil_price->product_code = $item['product_code'];
                    $fil_price->quantity = $item['quantity'];
                    $arr_price[] = $fil_price;
                }
                $product->prices()->saveMany($arr_price);
            }



            $productStorage = Storage::where('product_id', '=', $request->id)->get();
            for($i = 0;$i<count($productStorage);$i++) {
                $arr_store[] = ['prd_price_id' => $productStorage[$i]['prd_price_id'], 'warehouse_id' => $productStorage[$i]['warehouse_id'], 'amount' => $productStorage[$i]['amount']];
            }

            if(!empty($arr_store)) {
                Storage::where('product_id',$product->id)->delete();
                foreach ($arr_store as $item) {
                    $arr_store_ids = [];
                    $store = new Storage();
                    $store->prd_price_id = (int)$item['prd_price_id'];
                    $store->warehouse_id = (int)$item['warehouse_id'];
                    $store->created = strtotime(now());
                    $store->updated_at = strtotime(now());
                    $arr_store_ids[] = $store;
                }
                $product->store()->saveMany($arr_store_ids);
            }

            return \Lib::ajaxRespond(true, 'success',['url' => route('admin.product.edit.post', $product->id)]);
        }
        return \Lib::ajaxRespond(false, 'Đã có lỗi trong quá copy dữ liệu');
    }
}
