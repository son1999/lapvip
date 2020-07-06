<?php

namespace App\Modules\FrontEnd\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Feature;
use App\Models\GeoDistrict;
use App\Models\News;
use App\Models\Page;
use App\Models\Product;
use App\Models\GeoProvince;
use App\Models\Tag;
use App\Models\Warehouse;
use Symfony\Component\VarDumper\Dumper\DataDumperInterface;

class HomeController extends Controller
{   //
    public function __construct(){

    }

    public function index(){
        view()->share('isHome', true);


        $product_Tags = [];
        $tag_home = Tag::getTagsHome();
        foreach ($tag_home as $tags_pid){
            $id_sell = explode(',', $tags_pid['id_sell']);
            if ($tags_pid['is_show_slide_home'] == 1){
//                $product_slide ['slide'] = Product::where('status', '>', 1)->where('cat_id', $tags_pid['pid'])->limit(10)->get()->toArray();
                $product_slide ['slide'] = Product::getProductSlide($tags_pid['cid'], 10)->get()->toArray();
//                dd($product_slide['slide']);
            }
            if (!empty($id_sell)){
                $proSell['proSell']= Category::getCateByArrID($id_sell);
            }
            $product ['product'] = Product::where('status', '>', 1)->where('special_box_home', $tags_pid['id'])->limit(6)->get()->toArray();
            $product_Tags [] = array_merge($product, $tags_pid, @$product_slide??[], @$proSell??[]);
        }
        $cate = Category::getCat(1);
        foreach ($cate as $i_c){
            if ($i_c['title'] == 'Phụ kiện'){
                if(!empty($i_c['sub'])){
                    foreach ($i_c['sub'] as $key => $i_s){
                        if($key === array_key_first($i_c['sub'])){
                            $id_cate = $i_s['id'];
                            $product_by_cate = Product::getProductsByCate($id_cate, '', '', '', '','','')->limit(8)->get();
                        }
                    }
                }
            }
        }
        $trangtinh = Page::where('status', '>', 1)->where('show_home', '>', 0)->limit(1)->orderBy('id', 'DESC')->get();
        return view('FrontEnd::pages.home.index', [
            'site_title' => __('site.trangchu'),
            'slide' => Feature::getSlideByPositions('vi','big_home')->limit(6)->get(),
            'banner_right' => Feature::getSlideByPositions('vi', 'top_right_home')->limit(2)->get(),
            'banner_bottom' => Feature::getSlideByPositions('vi', 'bottom_center_of_big')->orderBy('id')->first(),
            'news' => News::getListNewHome('vi', 2),
            'product_tags' => $product_Tags,
//            'product_home' => $product,
//            'product_sell' => $proSell,
//            'product_slide_by_tags' => @$product_slide,
            'product_by_cate' => @$product_by_cate,
            'static' => $trangtinh,
//            'product_1' => Product::getProductsOneHot(),
//            'product_2' => Product::getProductsTwoHot(),
//            'product_3' => Product::getProductsThreeHot(),
//            'total' => Product::count(),
        ]);
    }
    public static function getDistrictList()
    {
        $input = request()->all();
        $district['data'] = GeoDistrict::where("Province_ID", $input['pro_id'])->orderBy('safe_title')->get();
        echo json_encode($district);
        exit;
    }
    public static function getWarehouse()
    {
        $input = request()->all();
        $warehouse['data'] = Warehouse::where("province_id", $input['pro_id'])->where('district_id', $input['dis_id'])->where('status', '>', 1)->orderBy('id')->get();
        echo json_encode($warehouse);
        exit;
    }
}
