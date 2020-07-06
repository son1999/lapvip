<?php

namespace App\Modules\FrontEnd\Controllers;

use App\Models\AnswerQuestion;
use App\Models\Collection;
use App\Models\Customer;
use App\Models\Feature;
use App\Models\Filter;
use App\Models\FilterCate;
use App\Models\InstallmentBank;
use App\Models\InstallmentDetail;
use App\Models\InstallmentScenarios;
use App\Models\InstallmentSuccess;
use App\Models\ProductDetail;
use App\Models\ProductPrices;
use App\Models\Supports;
use App\Models\Warehouse;
use App\Transformers\PrdPriceTranformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Comment;
use App\Models\Question;
use App\Models\Category;
use App\Models\ProductsViewed;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use League\Fractal;
use Illuminate\Support\Facades\Input;
use phpDocumentor\Reflection\DocBlock\Description;
use Validator;
class ProductController extends Controller
{
    const COMMENT_ACTIVE = '1';    // 1: active, 2: hide, -1: delete
    protected $recperpage = 15;
    public function __construct(){
        \Lib::addBreadcrumb();
        \View::share('cates'    , Category::getCat(1));
    }

    public function index(Request $request){
        $id = $request->child;
        $pid = $request->parent_id;
        $ins = $request->ins;

        $page_format = Category::find(!empty($id) && $id != 0 ? $id : $pid);

        $cate_product = Category::getCate($pid, $id);

        if (!empty($cate_product)){
            $cate_title = $cate_product->title;
        }
        if ($cate_product) {

            if (!empty($id)){
                \Lib::addBreadcrumb($cate_product->cate_parent_title, route('product.list', ['alias' => $request->alias, 'parent_id' => $request->parent_id]));
                \Lib::addBreadcrumb($cate_product->title);
            }else{
                \Lib::addBreadcrumb($cate_product->title, route('product.list', ['alias' => $request->alias, 'parent_id' => $request->parent_id]));
            }
//            dd($pid);
            $metaSeo = Category::getDataSeo(0, $pid);


            $filter_cate_child = FilterCate::getByPrdCate($id);
            $filter_cate_parent = FilterCate::getByPrdCate($pid);

            $sort_by = isset($request->sort_by) ? $request->sort_by : 3;
            $filter_ids = $request->filter_ids;
            $filter_ids = $filter_ids ? explode(',', $filter_ids) : '';
            $filter_cate  = FilterCate::getFilterCateByCate(!empty($id) && $id != 0 ? $id : $pid);
            $cateID = [];
            foreach ($filter_cate as $itemfi){
//                $filter_cate['pid'] = $pid;
                $cateID[] = $itemfi['filter_cate_id'];
            }
            $filter = Filter::getFilterByFilterCateID($cateID);
            $fil = FilterCate::fetchResult($filter);
            foreach ($filter_cate as &$item){
                foreach ($fil as $item_fil){
                    if ($item['id'] == $item_fil['filter_cate_id']){
                        $item['filters'][] = $item_fil;
                    }
                }
            };
            $choosed_filters = [];
            if (!empty($filter_ids)){
                foreach ($filter_cate as &$item) {
                    $item['haveCheck'] = 0;
                    foreach ($item['filters'] as &$item_filter) {
                        if (in_array($item_filter['id'], $filter_ids)) {
                            $item_filter['checked'] = 1;
                        } else {
                            $item_filter['checked'] = 0;
                        }
                        if($item_filter['checked'] == 1){
                            $item['haveCheck'] = 1;
                        }
                        if (in_array($item_filter['id'], $filter_ids)) {
                            $choosed_filters[] = [
                                'cate_title' => $item['title'],
                                'cate_id' => $item['filter_cate_id'],
                                'filter_title' => $item_filter['title'],
                                'filter_id' => $item_filter['id']
                            ];
                        }
                    }
                }
            }

            $collection = [];
            $collec = Collection::getCollectionByCat($pid);
            foreach ($collec as $item_co){
                $pro_collect ['products']  = Product::getPrqoductByCollect($item_co['collec_cate'])->limit(10)->get()->toArray();
                $collection[] = array_merge($pro_collect, $item_co);
            }
            $product = Product::getByCate(isset($id) && !empty($id) ? $id : $pid,$ins,'',$filter_ids,'',$sort_by)->paginate(15)->appends(Input::except('page'));
            if ($page_format->page_format == 4){
                $dataP =   Product::getProductsIns(!empty(\request()->ins) ? \request()->ins : '', !empty(\request()->id) ? \request()->id : '',12, $filter_ids, $sort_by);
            }
//            else{
//                $dataP =   Product::getByCate(!empty($id) && $id != 0 ? $id : $pid,$ins, '', $filter_ids,'',$sort_by)->paginate(15)->appends(Input::except('page'));
//            }


            $slide =  Feature::where('status','>', 0)->where('lang', 'vi')->where('cat_id', $pid)->get();
            $tpl = [];
            $tpl ['question'] = AnswerQuestion::where('status','>', 1)->where('qid', 0)->get();
            $tpl['answer_ques'] = AnswerQuestion::where('status', 1)->where('qid', '>', 0)->get();
            $tpl['paginate'] = AnswerQuestion::where('status','>', 1)->paginate(1,['*'],'cmt');
            $dataPage = [
                'site_title' => $cate_product->title,
                'slide' => $slide,
                'filter_cate_child' => $filter_cate_child,
                'filter_cate_parent' => $filter_cate_parent,
                'choosed_filters' => $choosed_filters ?? [],
                'sort_by' => $sort_by,
                'orderClauseText' => Product::orderClauseText(),
                'collec' => $collection,
                'data' => @$dataP,
                'comment'   => $tpl,
                'current_cate' => $cate_product,
                'filter_cates' => $filter_cate,
                'type' => 'menu_detail',
                'pro_mobile' => $product,
                'cate_title' => $cate_title,
                'metaSeo' => $metaSeo,

            ];

            switch ($page_format->page_format){
                case 1:
                    $view = 'FrontEnd::pages.product.index.index';
                    break;
//                case 2:
//                    $view = 'FrontEnd::pages.product.index.index2';
//                    break;
                case 3:
                    $view = 'FrontEnd::pages.product.index.index3';
                    break;
                case 4:
                    $view = 'FrontEnd::pages.product.index.tragop';
                    break;
                default:
                    return abort('404');
            }


            return view($view, $dataPage);
        }
        return abort('404');
    }



    public function filter(Request $request){
        $id = request()->child;
        $pid = \request()->cate;
        $page_format = Category::find( $pid);

        $cate_product = Category::getCate($pid, $id);
        if (!empty($cate_product)){
            if (!empty($id) && $id != 0){
                $cate_title['child'] = $cate_product->title;
                $cate_title['parent'] = $cate_product->cate_parent_title;
            }else{
                $cate_title['parent'] = $cate_product->title;
            }
        }


        if ($cate_product) {
            if (!empty($id) && $id != 0){
                \Lib::addBreadcrumb($cate_title['parent'], route('product.list', ['alias' => Str::slug($cate_title['parent']), 'parent_id' => $cate_product['pid']]));
                \Lib::addBreadcrumb($cate_title['child']);
            }else{
                \Lib::addBreadcrumb($cate_title['parent'], route('product.list', ['alias' => Str::slug($cate_title['parent']), 'parent_id' => $cate_product['pid']]));
            }



            $filter_cate  = FilterCate::getFilterCateByCate(!empty($id) && $id != 0 ? $id : $pid);

            $cateID = [];
            foreach ($filter_cate as $itemfi){
                $cateID[] = $itemfi['filter_cate_id'];
            }
            $filter = Filter::getFilterByFilterCateID($cateID);
            $fil = FilterCate::fetchResult($filter);
            foreach ($filter_cate as &$item){
                foreach ($fil as $item_fil){
                    if ($item['id'] == $item_fil['filter_cate_id']){
                        $item['filters'][] = $item_fil;
                    }
                }
            };

            $sort_by = isset($request->sort_by) ? $request->sort_by : 3;
            $filter_ids = \request()->filter_ids;
            $filter_ids = $filter_ids ? explode(',', $filter_ids) : '';
            $choosed_filters = [];
            $filter_child = request()->filter_child;

            if (!empty($filter_child)){
                $key_check_fil = 1;
                foreach ($filter_cate as &$cate) {
                    $cate['haveCheck'] = 0;
                    $cate['checkall'] = 0;
                    if (!empty($cate['filters'])){
                        foreach ($cate['filters'] as &$filter) {
                            if (Str::contains($filter_child,Str::slug($filter['title']))) {
                                $filter['checked'] = 1;
                                $cate['haveCheck'] = 1;
                            } else {
                                $filter['checked'] = 0;
                            }
                            if (Str::contains($filter_child,Str::slug($filter['title']))) {
                                $choosed_filters[] = [
                                    'cate_title' => $cate['title'],
                                    'cate_id' => $cate['filter_cate_id'],
                                    'filter_title' => $filter['title'],
                                    'filter_id' => $filter['id']
                                ];
                            }
                        }
                    }

                    if ($cate['show_filter'] == 0 && $key_check_fil < 3){
                        if ($cate['haveCheck'] == 0){
                            $cate['haveCheck'] = 1;
                            $cate['checkall'] = 1;
                        }else{
                            $cate['haveCheck'] = 1;
                        }
                        $key_check_fil ++;

                    }
                }
            }else{
                $key_check = 1;
                foreach ($filter_cate as &$item) {
                    if ($item['show_filter'] == 0 && $key_check < 3){
                        $item['haveCheck'] = 1;
                        $item['checkall'] = 1;
                        $key_check ++;
                    }
                }
            }

            if (!empty($filter_ids)){
                $key_check_fil = 1;
                foreach ($filter_cate as $key_item =>  &$item) {
                    $item['haveCheck'] = 0;
                    $item['checkall'] = 0;
                    foreach ($item['filters'] as $key => &$item_filter) {
                        if (!empty($item_filter['sub'])){
                            foreach ($item_filter['sub'] as &$item_filter_sub){
                                if (in_array($item_filter_sub['id'], $filter_ids)) {
                                    $item_filter_sub['checked'] = 1;
                                } else {
                                    $item_filter_sub['checked'] = 0;
                                }
                                if($item_filter_sub['checked'] == 1){
                                    $item['haveCheck'] = 1;
                                }
                                if (in_array($item_filter_sub['id'], $filter_ids)) {
                                    $choosed_filters[] = [
                                        'cate_title' => $item['title'],
                                        'cate_id' => $item['filter_cate_id'],
                                        'filter_title' => $item_filter_sub['title'],
                                        'filter_id' => $item_filter_sub['id']
                                    ];
                                }
                            }

                        }
                        if (in_array($item_filter['id'], $filter_ids)) {
                            $item_filter['checked'] = 1;
                        } else {
                            $item_filter['checked'] = 0;
                        }
                        if($item_filter['checked'] == 1){
                            $item['haveCheck'] = 1;
                        }
                        if (in_array($item_filter['id'], $filter_ids)) {
                            $choosed_filters[] = [
                                'cate_title' => $item['title'],
                                'cate_id' => $item['filter_cate_id'],
                                'filter_title' => $item_filter['title'],
                                'filter_id' => $item_filter['id']
                            ];
                        }
                    }
                    if ($item['show_filter'] == 0 && $key_check_fil < 3){
                        if ($item['haveCheck'] == 0){
                            $item['haveCheck'] = 1;
                            $item['checkall'] = 1;
                        }else{
                            $item['haveCheck'] = 1;
                        }
                        $key_check_fil ++;

                    }
                }
            }else{
                if(empty($filter_child)){
                    $key_check = 1;
                    foreach ($filter_cate as &$item) {
                        if ($item['show_filter'] == 0 && $key_check < 3){
                            $item['haveCheck'] = 1;
                            $item['checkall'] = 1;
                            $key_check ++;
                        }
                    }
                }
            }

            $dataP =   Product::getByCate(!empty($id) && $id != 0 ? $id : $pid,$ins = '',$filter_child, $filter_ids,'',$sort_by)->orderByRaw('sort DESC, created DESC')->paginate(15)->appends(Input::except('page'));
            if (\Lib::mobile_device_detect() == true){
                $slide =  Feature::where('status','>', 0)->where('lang', 'vi')->where('cat_id', $pid)->get();
            }

            $dataPage = [
                'site_title' =>$cate_product = 1 ? __('site.sanpham') : $cate_product->title,
                'slide' => @$slide,
                'filter_cates' => $filter_cate,
                'choosed_filters' => $choosed_filters ?? [],
                'choosed_filters_menu' => $choosed_filters_menu ?? [],
                'sort_by' => $sort_by,
                'orderClauseText' => Product::orderClauseText(),
                'data' => $dataP,
                'fil_id' => $filter_ids,
                'current_cate' => $cate_product,
//                'filter_cates' => FilterCate::getFilterCates(),
                'type' => 'menu_detail',
                'cate_title' => $cate_title,
            ];



            switch ($page_format->page_format){
                case 1:
                    $view = 'FrontEnd::pages.product.filter';
                    break;
//                case 2:
//                    $view = 'FrontEnd::pages.product.index.index2';
//                    break;
                case 3:
                    $view = 'FrontEnd::pages.product.index.index3';
                    break;
                case 4:
                    $view = 'FrontEnd::pages.product.index.tragop';
                    break;
                default:
                    return abort('404');
            }

            return view($view, $dataPage);
        }
        return abort('404');
    }

    public function detail($alias){
        $product = Product::getByAlias($alias)->first();
            $viewd = [];
            if (Auth::guard('customer')->check()) {
                $cus_id = Auth::guard('customer')->user()->id;
                if (empty(ProductsViewed::getProductViewedById($product->id, $cus_id))) {
                    ProductsViewed::createNewProductViewed($product->id, $cus_id);
                } else {
                    ProductsViewed::updateProductViewed($product->id, $cus_id);
                }
                $viewd = ProductsViewed::with('viewed')->where('cus_id', $cus_id)->orderByRaw('created DESC, id DESC')->paginate(8, ['*'], 'viewed');
            }
            if ($product) {
//                $warehouse = Warehouse::where('status', '>', 1)->select('title', 'supports')->orderBy('id', 'DESC')->first();
                $cate = Category::getCateById($product['cat_id']);
                if ($cate['show_detail_accessory'] == 0) {
                    //kho
                    $fractal = new Fractal\Manager();
                    $obj = ProductPrices::getByPrdId($product->id, true);

                    //            $abc = FilterCate::getFilterCates(false,$product->cat_id)->keyBy('id')->first()->toArray();
                    $abc = array_keys(FilterCate::getFilterCateByProID($product->id, $product->cat_id));
                    //            dd($abc);
                    //            $warehouse = Warehouse::getAll()->toArray();


                    //customer_group
                    $cus = Auth::guard('customer');
                    if (!empty($cus->check())) {
                        $customer_gp = Customer::with('groups')->where('id', $cus->user()->id)->first();
                        $percent = $customer_gp['groups']->max('percent');
                    }

                    $linked_prds = $product->linked_prds();
                    if ($cate->pid != 0){
                        $cate_parent = Category::getCateById($cate->pid);
                        \Lib::addBreadcrumb($cate_parent->title, route('product.list', ['alias'=> \Illuminate\Support\Str::slug($cate_parent['title']), 'parent_id' => $cate_parent['id']]));
                        \Lib::addBreadcrumb($cate->title, route('product.filter',['alias_filter' => \Illuminate\Support\Str::slug($cate->title), 'cate' => $cate['pid'], 'child' => $cate['id'], 'filter_child' => $cate['slug'] ]));
                        \Lib::addBreadcrumb($product->title);
                    }else{
                        \Lib::addBreadcrumb($cate->title, route('product.filter',['alias_filter' => \Illuminate\Support\Str::slug($product->category->title), 'cate' => $cate['pid'], 'child' => $cate['id'], 'filter_child' => $cate['slug'] ]));
                        \Lib::addBreadcrumb($product->title);
                    }
                    //have sale
                    $prd_have_sale = ProductDetail::where('product_id', $product->id)->first();
                    //            dd($prd_have_sale);
                    // GIá sản phẩm
                    $prd_price = ProductPrices::getByPrdId($product->id);
                    //            dd($prd_price);
                    // Tính trung bình sao
                    //máy tính tương đương
                    $equivalent = Product::where('cat_id', $product->cat_id)->where('status', '>', 1)->inRandomOrder(10)->get();
                    $tpl = [];
                    $tpl['comment'] = Comment::getCommentProductById($product->id)->orderBy('id', 'DESC')->get();
                    $tpl['paginate'] = Comment::getCommentProductById($product->id)->paginate(1, ['*'], 'cmt');
                    //            dd($tpl);
                    //            $tpl['paginate'] = Comment::getCommentProductById($product->id)->paginate(5,['*'],'cmt');
                    // dd(Comment::setRateCommentProduct($id));
                    //
                    if (count($tpl['comment']) > 0) {
                        $tpl['rating']['total'] = $tpl['comment']->count('rating');
                        $tpl['rating']['total_rate'] = $tpl['comment']->where('aid', '')->count('rating');
                        $tpl['rating']['avg'] = Comment::getSumRate($product->id);
                        // dd($tpl['rating']['avg']);
                        $tpl['rating']['avg'] = round($tpl['rating']['avg'] / $tpl['rating']['total_rate'], 1);
                        for ($i = 1; $i <= 5; $i++) {
                            $count = $tpl['comment']->where('rating', $i)->count('rating');
                            // $tpl['rating']['avg'] += $count;
                            $tpl['rating']['rating_' . $i] = $count / $tpl['rating']['total'] * 100;
                        }
                    }
                    // câu hỏi
                    $tpl['question'] = Question::getProductQuestionById($product->id);

                    $tpl['answer_ques'] = Question::getanswerbyQuesPID($product->id);
                    $tpl['count'] = Question::where('product_id', $product->id)->where('status', 1)->where('qid', '>', 0)->count();
                    //            dd($tpl);
                    //cùng hãng
                    $product_prices = $fractal->createData(new Fractal\Resource\Collection($obj['prices'], new PrdPriceTranformer))->toArray();
                    $product_manufacturer = Product::getProManu($abc)->limit(10)->get();

                    $cate_product = $product->category->id;
                    $p_start = ($product->price + (($product->price / 100) * 10));
                    $p_end = ($product->price - (($product->price / 100) * 10));

                    $product_compare = Product::getProductCompare($product->id, $cate_product, $p_start, $p_end, '',3)->get()->toArray();
                    $supports = Supports::getSup(5);
                    return view('FrontEnd::pages.product.detail', [
                        'site_title' => $product->title,
                        'may_interesting' => Product::maybeInteresting(8),
                        'data' => $product,
                        'linked_prds' => $linked_prds,
                        //                'collections' => Collection::where('lang', \Lib::getDefaultLang())->orderBy('created', 'DESC')->limit(3)->get(),
                        'type' => 'menu_detail',
                        'banner_detail' => Feature::getSlideByPositions('vi', 'detail')->orderBy('id')->first(),
                        'prd_prices' => $prd_price,
                        'prd_have_sale' => $prd_have_sale,
                        'equivalent' => $equivalent,
                        'pro_manu' => $product_manufacturer,
                        'prd_offer' => Product::with('category')->where('cat_id', $product->cat_id)->paginate(8, ['*'], 'like'),
                        'prd_history' => $viewd,
                        'comment' => $tpl,
                        'product_prices' => $product_prices,
                        //                'warehoue' => $warehouse,
                        'percent' => @$percent,
                        'customer_gp' => @$customer_gp,
                        'product_compare' => $product_compare,
                        'supports' => $supports,
                    ]);
                }
                abort(404);
            }
            abort(404);


    }

    public function detail_accessory($alias){
        $product = Product::getByAlias($alias)->first();
        if ($product){
            $cate = Category::getCateById($product['cat_id']);
            if ($cate['show_detail_accessory'] == 1) {
                \Lib::addBreadcrumb($product->category->title, route('product.list', ['alias' => str_slug($product->category->title), 'parent_id' => $product->category->pid, 'id' => $product->category->id]));
                \Lib::addBreadcrumb($product->title);
                $tpl = [];
                $tpl['question'] = Question::getProductQuestionById($product->id);
                $tpl['answer_ques'] = Question::getanswerbyQuesPID($product->id);
                $tpl['paginate'] = Question::getQuestionProductById($product->id)->paginate(1);
                $prd_have_sale = ProductDetail::where('product_id', $product->id)->first();
                $info = json_decode($prd_have_sale['properties']);
                $cate_product = $product->category->id;
                $product_accessory = Product::getProductAccessoryByCate($product->id, $cate_product, 4)->get()->toArray();

                return view('FrontEnd::pages.product.detail_accessory', [
                    'site_title' => $product->title,
                    'data' => $product,
                    'banner_detail' => Feature::getSlideByPositions('vi', 'detail')->orderBy('id')->first(),
                    'comment' => $tpl,
                    'inFo' => $info,
                    'product_accessory' => $product_accessory,
                ]);
            }
            abort(404);
        }
        abort(404);
    }

    public function searchByKey(Request $request)
    {
        $arr_key = explode(' ', $request->value);
        $only_integers = array_filter($arr_key,'ctype_digit');
        $only_string = array_diff_key($arr_key, array_flip((array) array_keys($only_integers)));
        $product = Product::getProductsByKey(implode(' ', $only_string), $only_integers, \request()->sort_by, 10)->get()->toArray();

        return response(json_encode($product));
    }

    public function searchKey(Request $request)
    {
        $arr_key = explode(' ', $request->key);
        $only_integers = array_filter($arr_key,'ctype_digit');
        $only_string = array_diff_key($arr_key, array_flip((array) array_keys($only_integers)));
        $product = Product::getProductsByKey(implode(' ', $only_string), $only_integers, \request()->sort_by)->paginate(16)->appends(Input::except('page'));
        \Lib::addBreadcrumb($request->key);
        return view('FrontEnd::pages.product.index.index2', [
            'site_title' => 'Kết quả tìm kiếm: '.$request->key,
            'data' => $product,
            'orderClauseText' => Product::orderClauseText(),
        ]);
    }

    public function promotion(Request $request) {
        \Lib::addBreadcrumb('Khuyến mãi');

        $sort_by = $request->sort_by;
        $cat_id = $request->cat_id;
        $keyword = $request->search;

        $khoiluong = $request->khoiluong;
        $khoiluong = $khoiluong ? explode(',',$khoiluong) : '';

        $list_products = Product::getByCate($cat_id,20,$khoiluong,$keyword,$sort_by);
//        dd($list_products);
        return view('FrontEnd::pages.product.promotion', [
            'site_title' => 'Khuyến mãi',
            'list_products' => $list_products,
            'keyword' => $keyword,
            'sort_by' => $sort_by,
        ]);
    }

    public function _saveComment(Request $request) {
        $tpl = [];
        $id = request('id');
        $obj = request('obj', []);
        $save = [
            'comment' => (isset($obj['comment']) && trim($obj['comment'])) ? $obj['comment'] : '',
            'rating' => (isset($obj['rating-product']) && trim($obj['rating-product'])) ? $obj['rating-product'] : '',
            'type_id' => $request->post_id,
            'uid' => Auth::guard('customer')->user()->id,
            'created' => time(),
            'type' => Comment::TYPEPRODUCT,
            'status' => self::COMMENT_ACTIVE,
            'comment_parent' => 0,
        ];

        if(!$id) {
            //thêm mới
            $id = Comment::insertGetId($save);
        }
        return back()->withInput();
    }

    public function _saveQuestion(Request $request) {
        $tpl = [];
        $qid = request('qid');
        $id = request('product_id');
        $obj = request('obj', []);

        if(!empty($qid)){
            //trả lời
            $ques = new Question();
            $ques->answer = $request->question;
            $ques->name = $request->name;
            $ques->email = $request->email;
            $ques->uid = !empty(Auth::guard('customer')->user()->id) ? Auth::guard('customer')->user()->id : '';
            $ques->product_id = $id;
            $ques->created = time();
            $ques->qid = $qid;
            $ques->status = self::COMMENT_ACTIVE;
            $ques->save();
        }elseif (!empty($id)){
            //thêm mới
            $ques = new Question();
            $ques->question = $request->question;
            $ques->name = $request->name;
            $ques->email = $request->email;
            $ques->uid = !empty(Auth::guard('customer')->user()->id) ? Auth::guard('customer')->user()->id : '';
            $ques->product_id = $id;
            $ques->created = time();
            $ques->status = self::COMMENT_ACTIVE;
            $ques->save();
        }
        return back()->withInput();
    }

    public function _saveProductViewed(Request $request) {
        $tpl = [];
        dd(sesion('user_id'));
        $obj = request('obj', []);
        $save = [
            'question' => $request->question,
            'uid' => rand(2, 3),
            'product_id' => $request->product_id,
            'created' => time(),
            'status' => self::COMMENT_ACTIVE,
        ];

        if(!$id) {
            //thêm mới
            $id = Question::insertGetId($save);
        }
        return back()->withInput();
    }

    public function installment_question(Request $request){
        $qid = request('qid');

        if(empty($qid)){
            $ques = new AnswerQuestion();
            $ques->question = $request->question;
            $ques->name = $request->name;
            $ques->email = $request->email;
            $ques->created = time();
            $ques->status = self::COMMENT_ACTIVE;
            $ques->save();
        }elseif (!empty($qid)){
//            trả lời
            $ques = new AnswerQuestion();
            $ques->question = $request->question;
            $ques->name = $request->name;
            $ques->email = $request->email;
            $ques->qid = $request->qid;
            $ques->created = time();
            $ques->status = self::COMMENT_ACTIVE;
            $ques->save();
        }
        return back()->withInput();
    }

    public function _installment(Request $request){
        $product = Product::getByPriceFilterKey($request->id,$request->filter_key,$request->quan + ( isset($current_quan) && $current_quan ? $current_quan : 0));
        if (!empty(@$product)){
            \Lib::addBreadcrumb($product->title);
        }
        if ($request->index == 0){
            $installment = InstallmentScenarios::where('status', '>', 1)->select('id', 'month')->get();
        }
        if ($request->index == 1){
            $installment_bank = InstallmentBank::where('status', '>', 1)->get();
        }
        return view('FrontEnd::pages.installment.index', [
            'site_title' => !empty($product->title) ? $product->title : '',
            'slide' => Feature::getSlideByPositions('vi', 'installment')->orderBy('id')->limit(6)->get(),
            'data' => !empty($product) ? $product : '',
            'installment' => @$installment,
            'installment_bank' => @$installment_bank,
        ]);
    }

    public function _saveInstallment(Request $request){
        $product = Product::getByPriceFilterKey($request->id,$request->filter_key,$request->quan + ( isset($current_quan) && $current_quan ? $current_quan : 0));
        if ($product){
            \Lib::addBreadcrumb($product->title);
            $installment = InstallmentScenarios::with('installment_scenarios')->where('id', $request->ins)->where('status', '>', 1)->first();
            $installment_detail = \GuzzleHttp\json_decode($installment->installment_scenarios->properties);

            //trả trước = giá máy * % trả trước / 100
            //trả mỗi tháng = ((giá máy - trả trước) / số tháng) + ((giá máy - trả trước) * (% tháng / 100 )) + phụ phí
            // tổng tiền trả góp = trả mỗi tháng * số tháng
            //tổng tiền sau trả góp = tổng tiền trả góp + trả trước
            //chênh lệch = tổng tiền sau trả góp - giá máy

            foreach($installment_detail as $item_detail){
                if ($item_detail->company == $request->com){
                    $pager = '';
                    foreach ($item_detail->pagers_required as $pagers_required){
                        $pager .= $pagers_required.' + ';
                    }
                    $installment_scenario = [];

                    $installment_scenario['month'] = $request->month;
                    $installment_scenario['pagers'] = $pager;
                    $installment_scenario['des'] = $item_detail->des;
                    $installment_scenario['prepay'] = $product->price * $item_detail->prepay / 100;
                    $installment_scenario['paymonth'] = (($product->price - $installment_scenario['prepay']) / $request->month) + (($product->price - $installment_scenario['prepay']) * ($item_detail->per_pay_mo / 100)) + $item_detail->surcharge;
                    $installment_scenario['total_cost'] = $installment_scenario['paymonth'] * $request->month;
                    $installment_scenario['total_af_ins'] = $installment_scenario['total_cost'] + $installment_scenario['prepay'];
                    $installment_scenario['difference'] = $installment_scenario['total_af_ins'] - $product->price;
                    $installment_scenario['img'] = $item_detail->image;
                    $installment_scenario['title_pro'] = $product->title;
                    $installment_scenario['price_pro'] = $product->price;

                    return view('FrontEnd::pages.installment.tragop2', [
                        'site_title' => !empty($product->title) ? $product->title : '',
                        'slide' => Feature::getSlideByPositions('vi', 'installment')->orderBy('id')->limit(6)->get(),
                        'data' => $installment_scenario,
                    ]);
                }
            }
        }
        else{
            return abort(404);
        }
    }

    public function _saveSuccessInstallment(Request $request){
        $validate = \Validator::make(
            $request->all(),
            [
                'buyer_sex' => 'required',
                'name' => 'required|string',
                'cmtnd' => 'required|numeric',
                'time_input' => 'required',
                'phone' => 'required|numeric|regex:/(0)[0-9]{9}/',
                'point_shop' => 'required',

            ],
            [
                'buyer_sex.required' => 'Vui lòng chọn giới tính',
                'name.required' => 'Vui lòng nhập Họ và Tên',
                'name.string' => 'Trường Họ và Tên phải là một chuỗi',
                'cmtnd.required' => 'Vui lòng nhập số CMND',
                'cmtnd.numeric' => 'Sai định dạng CMND',
                'time_input.required' => 'Vui lòng nhập đầy đủ Ngày / Tháng / Năm sinh',
                'phone.required' => 'Vui lòng nhập Số điện thoại',
                'phone.numeric' => 'Số điện thoại không đúng định dạng',
                'phone.regex' => 'Số điện thoại không đúng định dạng',
                'point_shop.required' => 'Vui lòng Chọn Shop muốn nhận hàng',
            ]
        );
        if ($validate->fails()) {
            return redirect()->back()->withInput()->withErrors($validate);
        }
        else{
            if (!empty(\request()->filter_key)){
                $filters = Filter::getWithCate(explode(',',\request()->filter_key));

                if(!empty($filters)) {
                    foreach($filters as $filter){
                        $temp = [
                            'filter_cate_title' => $filter->filter_cate->title,
                            'filter_value' => $filter->title
                        ];
                        $opt['meta'][] = $temp;
                    }
                }
            }

            $product = Product::getByPriceFilterKey($request->id,$request->filter_key,$request->quan + ( isset($current_quan) && $current_quan ? $current_quan : 0));
            \Lib::addBreadcrumb($product->title);
            $installment = InstallmentScenarios::where('id', $request->ins)->where('status', '>', 1)->first();
            $installment_detail = \GuzzleHttp\json_decode($installment->installment_scenarios->properties);

            foreach($installment_detail as $item_detail){
                if ($item_detail->company == $request->com){
                    $pager = '';
                    foreach ($item_detail->pagers_required as $pagers_required){
                        $pager .= $pagers_required.' + ';
                    }
                    $installment_scenario = [];

                    $installment_scenario['month'] = $request->month;
                    $installment_scenario['prepay'] = $product->price * $item_detail->prepay / 100;
                    $installment_scenario['paymonth'] = (($product->price - $installment_scenario['prepay']) / $request->month) + (($product->price - $installment_scenario['prepay']) * ($item_detail->per_pay_mo / 100)) + $item_detail->surcharge;
                    $installment_scenario['total_cost'] = $installment_scenario['paymonth'] * $request->month;
                    $installment_scenario['total_af_ins'] = $installment_scenario['total_cost'] + $installment_scenario['prepay'];
                    $installment_scenario['difference'] = $installment_scenario['total_af_ins'] - $product->price;
                }
            }

            $data = [];
            $data['_token'] = \request()->_token;
            $data['buyer_sex'] = $request->buyer_sex;
            $data['name'] = $request->name;
            $data['date_of_birth'] = $request->time_input;
            $data['cmtnd'] = $request->cmtnd;
            $data['phone'] = $request->phone;
            $data['ProID'] = \request()->id;
            $data['filter_key'] = @$opt['meta'] ? json_encode($opt['meta']) : '';
            $data['quan'] = \request()->quan;
            $data['type'] = \request()->index;
            $data['installment_scenarios_id'] = \request()->ins;
            $data['month'] = $installment_scenario['month'];
            $data['prepaid_amount'] = $installment_scenario['prepay'];
            $data['difference'] = $installment_scenario['difference'];
            $data['monthly_installments'] = $installment_scenario['paymonth'];
            $data['total_cost'] = $installment_scenario['total_af_ins'];
            @$data['point_shop'] = @$request->point_shop;
            $saveSuccess = InstallmentSuccess::pushInstallmentSuccess($data);
            if (empty($saveSuccess)){
                return redirect()->back()->with('thanhcong', ['success']);
            }
            else{
                return redirect()->back()->with('error', 'đã có lỗi trong quá trình lưu giữ liệu');
            }
        }
    }

    public function compare(Request $request){
        $pro_parent = $request->pro_parent;
        $pro_child = $request->pro_child;
        $product_par = Product::getByAlias($pro_parent)->first();
        $product_child = Product::getByAlias($pro_child)->first();
        $compare_parent = json_decode($product_par->detail->properties, true);
        $compare_child = json_decode($product_child->detail->properties, true);
        $prd_have_sale_parent = ProductDetail::where('product_id', $product_par->id)->first();
        $prd_have_sale_child = ProductDetail::where('product_id', $product_child->id)->first();

//        dd(Feature::getSlideByPositions('vi', 'compare')->orderBy('id')->first());

        return view('FrontEnd::pages.product.compare', [
            'site_title' => 'So sánh sản phẩm',
            'slide' => Feature::getSlideByPositions('vi', 'compare')->orderBy('id')->limit(6)->get(),
            'pro_p' => $product_par,
            'pro_c' => $product_child,
            'sale_parent' => json_decode($prd_have_sale_parent['promote_props'], true),
            'sale_child' => json_decode($prd_have_sale_child['promote_props'], true),
            'config_c' => $compare_child,
            'config_p' => $compare_parent,
        ]);

    }

}
