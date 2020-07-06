<?php

namespace App\Modules\FrontEnd\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Video;
use App\Models\VideoGroups;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Support\Facades\Input;

class NewsController extends Controller
{   //
    public function __construct(){
        \Lib::addBreadcrumb();
    }

    public function index($type = 'news'){
        \Lib::addBreadcrumb('Tin tức');
        $recperpage = 9;
        $lang = \Lib::getDefaultLang();
        $cond = [
            ['status', '>', 1],
            ['lang', '=', \Lib::getDefaultLang()],

        ];

        $cate = Category::find(request()->cat);
        $product = Product::where('status', '>', 1)->where('lang', \Lib::getDefaultLang())->orderBy('id')->limit(5)->get();
        $watch_a_lot = News::getWatchalot(request()->cat,\Lib::getDefaultLang() , 6);
        $news =  Category::getCat(2);
        if (!empty($cate)){
            if($cate->title == 'Video hot'){
                $v_hot = Video::getVideoHot($cate->id);
                $gropu = VideoGroups::getVideoByGruop($cate->id);
                return view('FrontEnd::pages.news.video_hot', [
                    'site_title' => 'Tin tức - Video hot',
                    'news' => $news,
                    'v_hot' => $v_hot,
                    'group' => $gropu,
                    'new_product' => $product,
                    'watch_a_lot' => $watch_a_lot,
//                    'related' => News::where([['lang', $lang]])->inRandomOrder()->orderBy('created', 'DESC')->paginate(5)->appends(Input::except('page_re')),
                ]);

            }else{
                $v_defaul = Video::getVideoDefaul($cate->id);
                $list_new_top = News::getNewTop($cond, $cate->id);
                $data = News::where($cond)->where('cat_id', '=', request()->cat)->whereNull('links_video')->where('hot_new', 0)->where('list_hot', 0)->orderBy('created', 'DESC')->paginate($recperpage)->appends(Input::except('page'));
            }
        }
        else{
            $v_defaul = Video::getVideoDefaul($cond);
            $list_new_top = News::getNewTop($cond);
            $data = News::where($cond)->whereNull('links_video')->where('hot_new', 0)->where('list_hot', 0)->orderBy('created', 'DESC')->paginate($recperpage)->appends(Input::except('page'));
        }
//        dd(@$data);

        return view('FrontEnd::pages.news.index', [
            'site_title' => 'Tin tức',
            'news' => $news,
            'data' => @$data,
//            'video_g' => @$vid_g,
//            'v_hot' => @$v_hot,
//            'group' => @$gropu,
//            'hot' => @$hot,
            'watch_a_lot' => $watch_a_lot,
            'v_defaul' => @$v_defaul,
            'new_product' => $product,
            'list_hot' => @$list_new_top,
//            'related' => News::where([['lang', $lang]])->inRandomOrder()->orderBy('created', 'DESC')->paginate(5)->appends(Input::except('page_re')),
        ]);
    }

    public function detail($cat_title, $alias, $type = 'news'){
        $news = News::with(['cates' => function($q){
            $q->select('id', 'title');
        }, 'user'] )->where('alias', $alias)->first();
        if (!empty($news)){
            $news['watch_a_lot'] += 1;
            $news->save();
        }

//        dd($news);
        $id_relate = explode(',', $news['id_news']);
        $reletd_new = News::where('status', '>', 1)->whereIn('id', $id_relate)->get();
        $cond = [
            ['status', '>', 1],
            ['lang', '=', \Lib::getDefaultLang()],
        ];
        if(!empty($news)){
            if($news->status > 1 && $news->published > 0){

                \Lib::addBreadcrumb('Tin tức',route('news.list', ['slug_title' => str_slug($news['title']) ]));
                $list_hot = News::where($cond)->whereNull('links_video')->where('list_hot', 1)->limit(3)->get();
                $lang = \Lib::getDefaultLang();
                if($lang != $news->lang){
                    Cookie::queue(\Lib::getLanguageKey(), $news->lang, 60*24*365);
                    \App::setLocale($lang);
                    return redirect()->route($type.'.detail', ['safe_title' => $alias]);
                }

                return view('FrontEnd::pages.news.detail', [
                    'site_title' => $news->title_seo,
                    'data' => $news,
                    'list_hot' => $list_hot,
                    'reletd_new' => $reletd_new,
//                    'newList' => News::getListNew($lang, 6, $news->id, $type),
//                    'related' => News::getRelated($lang, 5, $news->id, $type)
                ]);
            }
        }
        return redirect()->route('public.404');
    }

    public function indexPromotions(){
        return $this->index('promotions');
    }

    public function detailPromotions($safe_title, $id){
        return $this->detail($safe_title, $id, 'promotions');
    }

    public function indexBooks(){
        return $this->index('books');
    }

    public function detailBooks($safe_title, $id){
        return $this->detail($safe_title, $id, 'books');
    }
}
